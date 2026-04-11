module.exports = {
  apps: [
    {
      name: 'fju-smart-hub',
      script: 'php',
      args: 'artisan serve --host=0.0.0.0 --port=3000',
      cwd: '/home/user/webapp',
      env: {
        APP_ENV: 'local',
        APP_DEBUG: 'true',
      },
      watch: false,
      instances: 1,
      exec_mode: 'fork'
    }
  ]
}
