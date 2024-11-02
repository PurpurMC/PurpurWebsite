import sanitizeHtml from 'sanitize-html';

export default class Commit {
  author: string;
  email: string;
  description: string;
  hash: string;
  timestamp: number;

  static minecraftIssueRegex = new RegExp(/MC-[0-9]+/g);
  static githubIssueRegex = new RegExp(/#([0-9]+)/g);

  // this is NOT global to avoid exec iteration
  static upstreamCommitDescriptionLineRegex = new RegExp(/([\w-]+)\/([\w-]+)@(\w+)/);

  constructor(data: { [key: string]: unknown }) {
    this.author = this.getString(data, "author").trim();
    this.email = this.getString(data, "email").trim();
    this.hash = this.getString(data, "hash").trim();
    this.timestamp = this.getNumber(data, "timestamp");
    this.description = this.getString(data, "description").trim();
    this.description = sanitizeHtml(this.description, {});

    const isUpstreamDescription = this.description.startsWith("Updated Upstream") || this.description.includes("Upstream (");
    if (isUpstreamDescription) {
      this.description = this.hyperlinkUpstream(this.description);
    } else {
      this.description = this.hyperlinkGitHubIssue(this.description);
    }

    this.description = this.hyperlinkMojangIssue(this.description);
  }

  private hyperlinkUpstream(string: string): string {
    return string.split("\n").map((line, index) => {
      // first line is the Purpur commit header, so hyperlink as Purpur
      if (index === 0) {
        return this.hyperlinkGitHubIssue(line);
      }

      const upstreamMatch = line.match(Commit.upstreamCommitDescriptionLineRegex);
      if (upstreamMatch === null) {
        return line;
      }

      const tag = upstreamMatch[0];
      const orgName = upstreamMatch[1];
      const projName = upstreamMatch[2];
      const projHash = upstreamMatch[3];
      const commitAnchorTag = `<a href="https://github.com/${orgName}/${projName}/commit/${projHash}" target="_blank" rel="noreferrer">${tag}</a>`;

      const finalLine = line.replaceAll(tag, commitAnchorTag);
      return this.hyperlinkGitHubIssue(finalLine, orgName, projName);
    }).join("\n");
  }

  private hyperlinkGitHubIssue(string: string, orgName? : string, projName?: string): string {
    return this.hyperlinkText(string, Commit.githubIssueRegex, (regexMatch: RegExpExecArray) => {
      const issue = regexMatch[0];
      const issueNum = regexMatch[1];
      return `<a href="https://github.com/${orgName ?? "PurpurMC"}/${projName ?? "Purpur"}/issues/${issueNum}" target="_blank" rel="noreferrer">${issue}</a>`;
    });
  }

  private hyperlinkMojangIssue(string: string): string {
    return this.hyperlinkText(string, Commit.minecraftIssueRegex, (regexMatch: RegExpExecArray) => {
      const issue = regexMatch[0];
      return `<a href="https://bugs.mojang.com/browse/${issue}" target="_blank" rel="noreferrer">${issue}</a>`;
    });
  }

  private hyperlinkText(string: string, regex: RegExp, replacement: (regexMatch: RegExpExecArray) => string): string {
    let temp = string;
    for (const regexMatch of string.matchAll(regex)) {
      const anchorTag = replacement(regexMatch);
      temp = temp.replaceAll(regexMatch[0], anchorTag);
    }
    return temp;
  }

  private getString(data: { [key: string]: unknown }, key: string): string {
    if (typeof data[key] !== "string") {
      throw `Field expected to be a string: ${key}`
    }
    return data[key] as string;
  }

  private getNumber(data: { [key: string]: unknown }, key: string): number {
    if (typeof data[key] !== "number") {
      throw `Field expected to be a number: ${key}`
    }
    return data[key] as number;
  }

}
