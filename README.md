Thank you for reviewing my Referoo Assessment App.

I developed this on my local machine with Ubuntu.
I setup an NGINX server and got some keys for SSL so the referoo auth redirect would hit https://localhost, you may have to setup something similar to get that working.
This is a laravel breeze app with vue.js and inertia.

There is a mock sqlite database that is stored in database/database.sqlite. This is for the native authentication of the laravel breeze app, which i modified to use the referoo login and token.

You will need to install php 8.1 and composer
You will have to do: npm install, npm run build

Please let me know if you need more detailed instructions on how to get this app running.
I can put this in a docker container if that would be easier.
I can potentially host it on aws too if that will save you time, but we would need to do something about the api sandbox redirecting to https://localhost

My env file looks like this you will need to set it up to be the same, and add in the REFEROO_CLIENT_ID and REFEROO_CLIENT_SECRET.

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:Cb4BGQJRxrA0/DP2lSDI1WMpapE+ozHQsnF5YYTzq/E=
APP_DEBUG=true
APP_URL=https://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

REFEROO_CLIENT_ID=""
REFEROO_CLIENT_SECRET=""
REFEROO_REDIRECT_URI="https://localhost"





#Thank you and have a good day,
#Kind regards,
#Joel Wall
#Joel.samuel.wall@gmail.com