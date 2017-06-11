module.exports = function (grunt) {

    var
        componentsJsFiles = [
            'bower_components/underscore/underscore-min.js',
            'bower_components/angular/angular.min.js',
            'bower_components/angular-resource/angular-resource.min.js',
            'bower_components/angular-route/angular-route.min.js',
            'bower_components/angular-sanitize/angular-sanitize.min.js',
            'bower_components/angular-animate/angular-animate.min.js',     // Dépendence de UI-Bootstrap
            'bower_components/angular-touch/angular-touch.min.js',         // Dépendence de UI-Bootstrap
            'bower_components/angular-bootstrap/ui-bootstrap.min.js',
            'bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js',
            'web/bundles/fosjsrouting/js/router.js'
        ],
        projetJsFiles = [
            'web/js/fos_js_routes.js',
            'assets/js/**/*.js',
            'angularApp/Dependencies/*.js',
            'angularApp/Modules/*.js',
            'angularApp/Filters/*.js',
            'angularApp/Directives/*.js',
            'angularApp/Factories/Utils/*.js',
            'angularApp/Factories/Api/*.js',
            'angularApp/Controllers/**/*.js'
        ];

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        imagemin: {
            projetX: {
                files: [{
                    expand: true,                // Enable dynamic expansion
                    cwd: 'assets/img/',          // Src matches are relative to this path
                    src: ['**/*.{png,jpg,gif}'], // Actual patterns to match
                    dest: 'web/img/'             // Destination path prefix
                }]
            }
        },

        copy: {
            components: {
                files: [{
                    expand: true,
                    cwd: 'bower_components/font-awesome/fonts/',
                    src: ['**'],
                    dest: 'web/fonts/'
                }]
            },

            projetX: {
                files: [{
                    expand: true,
                    cwd: 'assets/fonts/',
                    src: ['**'],
                    dest: 'web/fonts/'
                }]
            }
        },

        htmlmin: {
            angularAppViews: {
                options: {
                    removeComments: true,
                    collapseWhitespace: true
                },
                files: [{
                    expand: true,
                    cwd: 'angularApp/Views/',
                    src: ['**/*.html'],
                    dest: 'web/views/'
                }]
            }
        },

        less: {
            components: {
                options: {
                    compress: true,
                    modifyVars: {}
                },
                files: {'web/css/components.min.css': 'assets/less/components.less'}
            },

            projetX: {
                options: {
                    compress: true,
                    modifyVars: {}
                },
                files: {'web/css/projet-x.min.css': 'assets/less/projet-x.less'}
            }
        },

        uglify: {
            components: {
                files: {'web/js/components.min.js': componentsJsFiles}
            },

            projetX: {
                files: {'web/js/projet-x.min.js': projetJsFiles}
            }
        },

        watch: {
            jsProjetX: {
                files: projetJsFiles,
                tasks: [
                    'uglify:projetX',
                    'concat:jsProjetX'
                ],
                options: {spawn: false}
            },

            lessProjetX: {
                files: ['assets/**/*.less'],
                tasks: [
                    'less:projetX',
                    'concat:lessProjetX'
                ],
                options: {spawn: false}
            },

            angularAppViews: {
                files: ['angularApp/Views/**/*.html'],
                tasks: ['htmlmin:angularAppViews'],
                options: {spawn: false}
            }
        },

        concat: {
            jsProjetX: {
                files: {
                    'web/js/javascript.min.js': [
                        'web/js/components.min.js',
                        'web/js/projet-x.min.js'
                    ]
                }
            },

            lessProjetX: {
                files: {
                    'web/css/stylesheet.min.css': [
                        'web/css/components.min.css',
                        'web/css/projet-x.min.css'
                    ]
                }
            }
        }
    });

    require('load-grunt-tasks')(grunt);

    grunt.registerTask('fonts', ['copy:components', 'copy:projetX']);
    grunt.registerTask('images', ['imagemin:projetX']);
    grunt.registerTask('javascripts', ['uglify:components', 'uglify:projetX', 'concat:jsProjetX']);
    grunt.registerTask('stylesheets', ['less:components', 'less:projetX', 'concat:lessProjetX']);
    grunt.registerTask('angularAppViews', ['htmlmin:angularAppViews']);

    grunt.registerTask('default', ['fonts', 'images', 'javascripts', 'stylesheets']);
    grunt.registerTask('projetX', ['less:projetX', 'concat:lessProjetX', 'uglify:projetX', 'concat:jsProjetX', 'htmlmin:angularAppViews']);
};