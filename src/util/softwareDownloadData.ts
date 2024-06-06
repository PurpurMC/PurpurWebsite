type Software = {
  name: string,
  getVersions: () => Promise<Version[]>,
}

type Version = {
  version: string,
  downloadLink: string,
  minecraftVersions: string[],
}

export default function softwareDownloadData(software: string): Software | null {
  switch (software) {
    case "purpurextras": return {
      name: "PurpurExtras",
      getVersions: async function () {
        const result = await fetch("https://api.modrinth.com/v2/project/purpurextras/version")
          .catch(() => {
            return null;
          });
        if (result == null) return [];
        const versions = await result.json();
        return versions.map((version: any) => {
          return {
            version: version.version_number,
            downloadLink: version.files[0].url,
            minecraftVersions: version.game_versions,
          }
        });

      }
    }
    default: return null;
  }
}