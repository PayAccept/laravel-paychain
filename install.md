**Upload a new package**

```
git add .
git commit -m "initial commit"
git tag 1.0.0

git push -u origin --all
git push -u origin --tags
```

**Install into the root composer.json**

```
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
```
