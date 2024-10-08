import Commit from "./Commit.ts";

export default class PurpurBuild {
  readonly project: string;
  readonly version: string;
  readonly build: string;
  readonly result: string;
  readonly timestamp: number;
  readonly duration: number;
  readonly md5: string;
  readonly commits: Commit[] = [];
  readonly metadata: {[key: string]: unknown} = {};

  constructor(data: {[key: string]: unknown}) {
    this.project = this.getString(data, "project");
    this.version = this.getString(data, "version");
    this.build = this.getString(data, "build");
    this.result = this.getString(data, "result");
    this.timestamp = this.getNumber(data, "timestamp");
    this.duration = this.getNumber(data, "duration");
    this.md5 = this.getString(data, "md5");
    for (const commit of data["commits"] as {[key: string]: unknown}[]) {
      this.commits.push(new Commit(commit));
    }
    this.metadata = data["metadata"] as {[key: string]: unknown};
  }

  private getString(data: {[key: string]: unknown}, key: string): string {
    if (typeof data[key] !== "string") {
      throw `Field expected to be a string: ${key}`
    }
    return data[key] as string;
  }

  private getNumber(data: {[key: string]: unknown}, key: string): number {
    if (typeof data[key] !== "number") {
      throw `Field expected to be a number: ${key}`
    }
    return data[key] as number;
  }

}