Using Dropzone, the AWS Async project's Simple S3 Client, and Symfony's Messenger component to decouple handling of uploads to S3 and deletion of uploads from S3.

AWS Async:  https://async-aws.com/integration/simple-s3.html

Symfony Messenger: https://symfony.com/doc/current/components/messenger.html

Dropzone: https://docs.dropzone.dev/

**Uploads**
1. Dropzone sends the files to an upload handler route
2. The upload handler moves the files from the PHP temp directory into a different temp directory
3. Once the file is moved, a message is dispatched for a worker to move the temp file to S3 and remove the temp file from the server

**Deletes**
1. A message is dispatched with the S3 filename (key) that is to be deleted by a worker 
2. Then the file record is removed from the database

<hr>
Notes:

- This project needs more error handling as most of these actions are fire and forget so if something fails to happen it should be tracked and re-attempted or logged or rolled back to a "known-good" state.
- Need to add download/streaming of files from s3 back to the user

