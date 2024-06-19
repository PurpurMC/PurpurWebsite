export default class PurpurExtrasVersion {
  readonly version: string;
  readonly changelog: string;
  readonly timestamp: number;
  readonly downloadUrl: string;

  constructor(
    version: string,
    changelog: string,
    timestamp: number,
    downloadUrl: string
  ) {
    this.version = version.replaceAll("v", "");
    this.changelog = changelog;
    this.timestamp = timestamp;
    this.downloadUrl = downloadUrl;
  }
}