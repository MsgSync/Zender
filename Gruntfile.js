/**
 * Gruntfile for Zender front-end build.
 *
 * Compiles templates/_scss/{default,dashboard}.scss into the theme stylesheets
 * served by assets("css/style.min.css"), mirroring the runtime compilation in
 * system/controllers/ajax.php (which passes the admin theme colors).
 *
 * Usage:
 *   grunt                 Compile both themes
 *   grunt sass:default     Compile templates/default only
 *   grunt sass:dashboard   Compile templates/dashboard only
 *   grunt watch            Rebuild on SCSS changes
 *   grunt --theme=#ff0000 --theme-text=#000000   Override theme variables
 */

module.exports = function (grunt) {
    const path = require("path");
    const sass = require("sass");

    const theme = grunt.option("theme") || "#2f3237";
    const themeText = grunt.option("theme-text") || "#ffffff";
    const scssDir = path.join(__dirname, "templates", "_scss");

    grunt.initConfig({
        sass: {
            options: {
                theme: theme,
                themeText: themeText,
                loadPaths: [scssDir]
            },
            default: {
                src: "templates/_scss/default.scss",
                dest: "templates/default/assets/css/style.min.css"
            },
            dashboard: {
                src: "templates/_scss/dashboard.scss",
                dest: "templates/dashboard/assets/css/style.min.css"
            }
        },
        watch: {
            scss: {
                files: ["templates/_scss/**/*.scss"],
                tasks: ["sass"]
            }
        }
    });

    grunt.registerMultiTask("sass", "Compile theme SCSS to minified CSS", function () {
        const options = this.options({
            theme: "#2f3237",
            themeText: "#ffffff",
            loadPaths: []
        });

        this.files.forEach((file) => {
            const result = sass.compileString(
                `$theme: ${options.theme};\n` +
                    `$themeText: ${options.themeText};\n` +
                    `@import '${path.basename(file.src[0])}';`,
                {
                    loadPaths: options.loadPaths,
                    style: "compressed",
                    silenceDeprecations: ["import", "global-builtin", "color-functions"]
                }
            );

            grunt.file.write(file.dest, result.css);
            grunt.log.writeln(`File "${file.dest}" created.`);
        });
    });

    grunt.loadNpmTasks("grunt-contrib-watch");

    grunt.registerTask("default", ["sass"]);
};
