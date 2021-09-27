How to update and install

1. git add .
2. git commit -m 'new update'
2a. git tag 1.0.1
3. git push -u origin --all
4. git push -u origin --tags

Add into the root composer.json the following:

...
"require": {
"php": "^7.1.3",
"fideloper/proxy": "^4.0",
"laravel/framework": "5.7.*",
"laravel/tinker": "^1.0",
"payaccept/laravel-paychain": "^1.0.0"
},
...
"repositories": [
{
"type": "vcs",
"url": "https://github.com/payaccept/laravel-paychain"
}
]
...
