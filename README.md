# Bones Theme
An empty framework to build Wordpress themes with Gutenberg using WPack.io for tooling. 

For more information on development setup see [wpack.io](https://wpack.io/).

## Setup

```bash
npx @wpackio/cli
npm run bootstrap
composer require wpackio/enqueue
```

#### In ```wpackio.server.js```
- Set a ```proxy``` value to your local URL *http://localhost:8888*
- Set ```host``` to your server's IP address if working remotely

#### In ```wpackio.project.js``` to reflect the directory of your 
- Update ```slug``` value theme directory name *bt-folder-name*

### Development 
```bash
npm run start
```

### Build
```bash
npm run build
```

### Deploy
```bash
npm run archive
```

### Git FTP
Set Git FTP settings with 'syncroot' pointing at packages directory. You may need to export any templates if these have been edited in WP.

```
[git-ftp]
        url = ftpes://1.1.1.1/public_html/wp-content/themes/{directory_name}
        user = "username"
        password = "password"
        insecure = 1
        syncroot = package/{directory_name}
```

### Fonts
Gotham Narrow

16/22 Med Nav

56/64 Bol Heading 1             3.5rem          1.142857em
36/40
34/42 Bol Heading 2             2.125rem        1.235294em
28/36
24/30 Med Heading 3             1.5rem          1.25em
16/22
20/24 Med Heading 4             1.25rem         1.2em
16/22
16/24 Med Heading 5             1rem            1.5em
16/22
14/22 Med Heading 6             0.875rem        1.571428em
14/22

20/30 Med Paragraph Large       1.25rem         1.5em
16/24
16/24 Med Paragraph             1rem            1.5em
16/24

14/22 Med Excerpt               0.875rem        1.571428em
13/16 Med Date                  0.8125rem       1.230769em
20/16 Med Author                1.25rem         0.8em

16/24 Med Button                1rem            1.5em

