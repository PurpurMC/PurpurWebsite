export default class Commit {
  author: string;
  email: string;
  description: string;
  hash: string;
  timestamp: number;

  constructor(data: {[key: string]: unknown}) {
    this.author = this.getString(data, "author");
    this.email = this.getString(data, "email");
    this.description = this.getString(data, "description");
    this.hash = this.getString(data, "hash");
    this.timestamp = this.getNumber(data, "timestamp");
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