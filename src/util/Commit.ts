import sanitizeHtml from 'sanitize-html';

export default class Commit {
  author: string;
  email: string;
  description: string;
  hash: string;
  timestamp: number;

  static minecraftIssueRegex = new RegExp(/MC-[0-9]+/g);

  constructor(data: { [key: string]: unknown }) {
    this.author = this.getString(data, "author").trim();
    this.email = this.getString(data, "email").trim();
    this.hash = this.getString(data, "hash").trim();
    this.timestamp = this.getNumber(data, "timestamp");
    this.description = this.getString(data, "description").trim();
    this.description = sanitizeHtml(this.description, {});
    for (const issueTag of this.description.matchAll(Commit.minecraftIssueRegex)) {
      const tag = issueTag[0];
      const anchorTag = `<a href="https://bugs.mojang.com/browse/${tag}" target="_blank" rel="noreferrer">${tag}</a>`;
      this.description = this.description.replaceAll(tag, anchorTag);
    }
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
