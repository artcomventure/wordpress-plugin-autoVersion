var gulp = require( 'gulp' ),

    // gulp plugins

    replace = require( 'gulp-replace' ),
    del = require( 'del' ),
    concat = require( 'gulp-concat' ),
    gettext = require( 'gulp-gettext' ),
    uglify = require( 'gulp-uglify' ),
    rename = require( 'gulp-rename' ),

    // doesn't break pipe on error
    // so we don't need to restart gulp
    plumber = require( 'gulp-plumber' ),
    // get notification on error
    notify = require( 'gulp-notify' ),
    onError = function( error ) {
        notify.onError( {
            title:    'Gulp Failure :/',
            message:  '<%= error.message %>',
            sound:    'Beep'
        } )( error );

        this.emit( 'end' );
    };

/**
 * Compile .po files to .mo
 */
var poFiles = ['./**/languages/*.po'];
gulp.task( 'po2mo', function() {
    return gulp.src(poFiles)
        .pipe( gettext() )
        .pipe( gulp.dest( '.' ) )
} );

/**
 * Compress and uglify js files.
 */
var jsFiles = ['js/**/*.js', '!**/*.min.js'];
gulp.task( 'js', function() {
    return gulp.src( jsFiles, { base: './' } )
        .pipe( plumber( { errorHandler: onError } ) )
        // rename to FILENAME.min.js
        .pipe( rename( { suffix: '.min' } ) )
        // uglify and compress
        .pipe( uglify() )
        .pipe( gulp.dest( './' ) );
} );

/**
 * Clear build/ folder.
 */
gulp.task( 'clear:build', function() {
    del.sync( 'build/**/*' );
} );

gulp.task( 'build', ['clear:build'], function() {
    // collect all needed files
    gulp.src( [
        '**/*',
        // ... but:
        '!**/*.scss',
        '!**/*.css.map',
        '!**/*.css', // will be collected see next function
        '!*.md',
        '!readme.txt',
        '!gulpfile.js',
        '!package.json',
        '!.csscomb.json',
        '!.gitignore',
        '!node_modules{,/**}',
        '!build{,/**}',
        '!assets{,/**}'
    ] ).pipe( gulp.dest( 'build/' ) );

    // collect css files
    gulp.src( [ '**/*.css', '!node_modules{,/**}' ] )
        .pipe( gulp.dest( 'build/' ) );

    // concat files for WP's readme.txt
    // manually validate output with https://wordpress.org/plugins/about/validator/
    gulp.src( [ 'readme.txt', 'README.md', 'CHANGELOG.md' ] )
        .pipe( concat( 'readme.txt' ) )
        // remove screenshots
        // todo: scrennshot section for WP's readme.txt
        .pipe( replace( /\n\!\[image\]\([^)]+\)\n/g, '' ) )
        // WP markup
        .pipe( replace( /#\s*(Changelog)/g, "## $1" ) )
        .pipe( replace( /###\s*([^(\n)]+)/g, "=== $1 ===" ) )
        .pipe( replace( /##\s*([^(\n)]+)/g, "== $1 ==" ) )
        .pipe( replace( /==\s(Unreleased|[0-9\s\.-]+)\s==/g, "= $1 =" ) )
        .pipe( replace( /#\s*[^\n]+/g, "== Description ==" ) )
        .pipe( gulp.dest( 'build/' ) );
} );

/**
 * Watch tasks.
 *
 * Init watches by calling 'gulp' in terminal.
 */
gulp.task( 'default', function() {
    gulp.watch( poFiles, ['po2mo'] );
} );
