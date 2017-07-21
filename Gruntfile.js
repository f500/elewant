module.exports = function (grunt) {
    'use strict';

    grunt.util.linefeed = '\n';

    RegExp.quote = function (string) {
        return string.replace(/[-\\^$*+?.()|[\]{}]/g, '\\$&')
    };

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        clean: {
            build: 'web/build'
        },

        copy: {
            jquery: {
                files: {
                    'web/build/js/jquery.js': 'node_modules/jquery/dist/jquery.js',
                    'web/build/js/jquery.min.js': 'node_modules/jquery/dist/jquery.min.js',
                    'web/build/js/jquery.min.map': 'node_modules/jquery/dist/jquery.min.map'
                }
            },
            'jquery-easing': {
                files: {
                    'web/build/js/jquery-easing.js': 'node_modules/jquery-easing/dist/jquery.easing.1.3.umd.js',
                    'web/build/js/jquery-easing.min.js': 'node_modules/jquery-easing/dist/jquery.easing.1.3.umd.min.js',
                    'web/build/js/jquery-easing.min.js.map': 'node_modules/jquery-easing/dist/jquery.easing.1.3.umd.min.js.map'
                }
            },
            tether: {
                files: {
                    'web/build/js/tether.js': 'node_modules/tether/dist/js/tether.js',
                    'web/build/js/tether.min.js': 'node_modules/tether/dist/js/tether.min.js'
                }
            },
            bootstrap: {
                files: {
                    'web/build/js/bootstrap.js': 'node_modules/bootstrap/dist/js/bootstrap.js',
                    'web/build/js/bootstrap.min.js': 'node_modules/bootstrap/dist/js/bootstrap.min.js'
                }
            },
            agency: {
                files: {
                    'web/build/js/agency.js': 'node_modules/startbootstrap-agency/js/agency.js',
                    'web/build/js/agency.min.js': 'node_modules/startbootstrap-agency/js/agency.min.js'
                }
            },
            'agency-images': {
                expand: true,
                cwd: 'node_modules/startbootstrap-agency/img',
                src: '**',
                dest: 'web/build/img/'
            },
            'font-awesome': {
                files: {
                    'web/build/fonts/FontAwesome.otf': 'node_modules/font-awesome/fonts/FontAwesome.otf',
                    'web/build/fonts/fontawesome-webfont.eot': 'node_modules/font-awesome/fonts/fontawesome-webfont.eot',
                    'web/build/fonts/fontawesome-webfont.svg': 'node_modules/font-awesome/fonts/fontawesome-webfont.svg',
                    'web/build/fonts/fontawesome-webfont.ttf': 'node_modules/font-awesome/fonts/fontawesome-webfont.ttf',
                    'web/build/fonts/fontawesome-webfont.woff': 'node_modules/font-awesome/fonts/fontawesome-webfont.woff',
                    'web/build/fonts/fontawesome-webfont.woff2': 'node_modules/font-awesome/fonts/fontawesome-webfont.woff2'
                }
            },
            elewant: {
                files: {
                    'web/build/js/elewant.js': 'src/Elewant/FrontendBundle/Resources/assets/js/elewant.js'
                }
            }
        },

        watch: {
            src: {
                files: 'src/Elewant/FrontendBundle/Resources/assets/js/elewant.js',
                tasks: ['copy:elewant', 'exec:uglify']
            },
            sass: {
                files: 'src/Elewant/FrontendBundle/Resources/assets/scss/*.scss',
                tasks: ['build-css']
            }
        },

        exec: {
            'clean-css': {
                command: 'npm run clean-css'
            },
            postcss: {
                command: 'npm run postcss'
            },
            sass: {
                command: 'npm run sass'
            },
            uglify: {
                command: 'npm run uglify'
            }
        }

    });

    require('load-grunt-tasks')(grunt);

    grunt.registerTask('build-js', ['copy', 'exec:uglify']);
    grunt.registerTask('build-css', ['exec:sass', 'exec:postcss', 'exec:clean-css']);
    grunt.registerTask('build', ['clean:build', 'build-css', 'build-js']);
    grunt.registerTask('default', ['build']);
};
