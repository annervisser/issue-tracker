# IssueTracker

This project was generated using [Nx](https://nx.dev).

| [Nx Documentation](https://nx.dev) | [Nx Plugin Directory](https://nx.dev/community#plugin-directory) |
|------------------------------------|------------------------------------------------------------------|

## Generating packages

### App

```shell
nx g @nrwl/angular:app appName
```

### Library

Libraries can be imported from `@issue-tracker/libName`.

```shell
nx g @nrwl/angular:lib libName
```

## Code scaffolding

| Type                | Command                                                            |
|---------------------|--------------------------------------------------------------------|
| Component           | `nx g component my-component --project=appName`                    |
| Service             | `nx g service my-service --project=appName`                        |
| Pipe                | `nx g pipe my-pipe --project=appName`                              |
| SCAM&nbsp;component | `nx g @nrwl/angular:scam my-component --project=appName`           |
| SCAM&nbsp;directive | `nx g @nrwl/angular:scam-directive my-component --project=appName` |
| SCAM&nbsp;pipe      | `nx g @nrwl/angular:scam-pipe my-component --project=appName`      |

## Development server

Run `nx serve my-app` for a dev server.\
Navigate to http://localhost:4200/.

Run `nx run-many --target=serve --all` to serve both angular and the api

## Build

Run `nx build my-app` to build the project.\
The build artifacts will be stored in the `dist/` directory.\
Use the `--prod` flag for a production build.

## Running tests

Run `nx test` to execute the unit tests via [Jest](https://jestjs.io).

Run `nx affected:test` to execute the unit tests affected by a change.

Run `nx e2e issue-tracker-e2e` to execute the end-to-end tests via [Cypress](https://www.cypress.io).

Run `nx affected:e2e` to execute the end-to-end tests affected by a change.

## Understand your workspace

Run `nx graph` to see a diagram of the dependencies of your projects.
