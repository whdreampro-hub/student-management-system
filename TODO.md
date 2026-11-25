# Project maintenance

## Completed

- Resolved the previously documented merge conflicts.
- Removed the web-accessible setup endpoint that could reset the administrator password to a known default.
- Moved database initialization to the CLI-only `scripts/setup.php` command.
- Removed the default administrator seed from the schema.
- Excluded the database dump from the current source tree and added ignore rules for local database and photo data.
- Switched database connection settings to environment variables.

## Remaining work

- Add automated tests for authentication, student CRUD, promotions, and transfers.
- Add CSRF protection to every state-changing form.
- Validate uploaded photo MIME types, file sizes, and generated filenames on the server.
- Review authorization checks for all administrative routes before production use.
- Add a documented backup and restore procedure.
