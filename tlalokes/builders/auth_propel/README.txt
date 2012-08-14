Authentication with Propel builder for Tlalokes.

This builder is not completly automatic, there are some tasks to do:

0. Activate the auth_propel builder by setting true the value of the builder in
   your application's configuration file (config.php).

   Example: $c['execute']['auth_propel'] = true;

1. Execute the auth_propel builder.
   The builder will copy some files (definition objects, controllers, models and
   views) to your application's directory. To execute, just load in your browser
   http://SERVER/[DIR/]exe/load where SERVER is the name of your server and
   [DIR/] is the directory of your site.

   Example with DIR: http://example.org/blog/exe/load
   Example without DIR: http://example.org/exe/load

2. Deactivate the auth_propel builder by setting false the value of the builder
   in your application's configuration file (config.php).
   $c['execute']['auth_propel'] = false;

3. Set the Propel mode to buid-all in your application's configuration file
   (config.php).

   Example: $c['mode']['propel'] = 'build-all';

4. Execute the propel mode.
   This action will create the required tables in the database and generate the
   necesary code.

5. Set the Propel mode from buil-all to production in the configuration file.

   Example: $c['mode']['propel'] = 'production';

6. Load your new catalogs and use it. Just load in your browser
   http://SERVER/[DIR/]auth_users where SERVER is the name of your server and
   [DIR/] is the directory of your site.

   Example with DIR: http://example.org/blog/auth
   Example without DIR: http://example.org/exe/auth
