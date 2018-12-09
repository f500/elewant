module.exports = function (grunt) {
    'use strict';

    grunt.util.linefeed = '\n';

    RegExp.quote = function (string) {
        return string.replace(/[-\\^$*+?.()|[\]{}]/g, '\\$&')
    };

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        clean: {
            build: 'public/build'
        },

        concat: {
            src: {
                src: [
                    'src/Elewant/Webapp/Application/Resources/assets/js/agency-theme.js',
                    'src/Elewant/Webapp/Application/Resources/assets/js/elewant.js'
                ],
                dest: 'public/build/js/elewant.js'
            }
        },

        copy: {
            jquery: {
                files: {
                    'public/build/js/jquery.js': 'node_modules/jquery/dist/jquery.js',
                    'public/build/js/jquery.min.js': 'node_modules/jquery/dist/jquery.min.js',
                    'public/build/js/jquery.min.map': 'node_modules/jquery/dist/jquery.min.map'
                }
            },
            'jquery-easing': {
                files: {
                    'public/build/js/jquery-easing.js': 'node_modules/jquery-easing/dist/jquery.easing.1.3.umd.js',
                    'public/build/js/jquery-easing.min.js': 'node_modules/jquery-easing/dist/jquery.easing.1.3.umd.min.js',
                    'public/build/js/jquery-easing.min.js.map': 'node_modules/jquery-easing/dist/jquery.easing.1.3.umd.min.js.map'
                }
            },
            'jquery-easy-autocomplete': {
                files: {
                    'public/build/js/jquery.easy-autocomplete.js': 'node_modules/easy-autocomplete/dist/jquery.easy-autocomplete.js',
                    'public/build/js/jquery.easy-autocomplete.min.js': 'node_modules/easy-autocomplete/dist/jquery.easy-autocomplete.min.js'
                }
            },
            popper: {
                files: {
                    'public/build/js/popper.js': 'node_modules/popper.js/dist/umd/popper.js',
                    'public/build/js/popper.js.map': 'node_modules/popper.js/dist/umd/popper.js.map',
                    'public/build/js/popper.min.js': 'node_modules/popper.js/dist/umd/popper.min.js',
                    'public/build/js/popper.min.js.map': 'node_modules/popper.js/dist/umd/popper.min.js.map'
                }
            },
            bootstrap: {
                files: {
                    'public/build/js/bootstrap.js': 'node_modules/bootstrap/dist/js/bootstrap.js',
                    'public/build/js/bootstrap.min.js': 'node_modules/bootstrap/dist/js/bootstrap.min.js'
                }
            },
            'font-awesome': {
                files: {
                    'public/build/fonts/FontAwesome.otf': 'node_modules/font-awesome/fonts/FontAwesome.otf',
                    'public/build/fonts/fontawesome-webfont.eot': 'node_modules/font-awesome/fonts/fontawesome-webfont.eot',
                    'public/build/fonts/fontawesome-webfont.svg': 'node_modules/font-awesome/fonts/fontawesome-webfont.svg',
                    'public/build/fonts/fontawesome-webfont.ttf': 'node_modules/font-awesome/fonts/fontawesome-webfont.ttf',
                    'public/build/fonts/fontawesome-webfont.woff': 'node_modules/font-awesome/fonts/fontawesome-webfont.woff',
                    'public/build/fonts/fontawesome-webfont.woff2': 'node_modules/font-awesome/fonts/fontawesome-webfont.woff2'
                }
            }
        },

        watch: {
            src: {
                files: 'src/Elewant/Webapp/Application/Resources/assets/js/elewant.js',
                tasks: ['copy:elewant', 'exec:uglify']
            },
            sass: {
                files: 'src/Elewant/Webapp/Application/Resources/assets/scss/*.scss',
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

    grunt.registerTask('build-js', ['concat', 'copy', 'exec:uglify']);
    grunt.registerTask('build-css', ['exec:sass', 'exec:postcss', 'exec:clean-css']);
    grunt.registerTask('build', ['clean:build', 'build-css', 'build-js']);
    grunt.registerTask('default', ['build']);
};
