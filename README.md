![Build Status](https://img.shields.io/github/workflow/status/MaximGrynykha/path2/Build?label=build&logo=github&logoColor=white&style=for-the-badge)

# __Path2__

If you are not confident that your program will properly lead the way to the required file or directory - use Path2, it automatically converts all slashes to those which are used in the current operating system, yet so over also will trim excess slashes if there are in the path.

## ⚙️ Installation

To install this library - run the command below in your terminal:

```shell
composer require maximgrynykha/path2
```

## Usage  

```php
<?php

use Path2\Path;

require_once 'vendor/autoload.php';

// For example, normalize some kinky path
$normalized = Path::to('/\/src/\\\Path2/\/\/\Path.php');

dd($normalized); // "{CWD}/src/Path2/Path.php" (on an UNIX) || "{CWD}\src\Path2\Path.php" (on a Windows)
                 
                 // Note, by default if any preceding to the main path (first argument),
                 // from-path (second argument) isn't passed then Path::to()
                 // uses CWD (current working directory) as a preceding, from-path.
```

### API
| Param  | Argument                                          | Example                              |
|:-------|:--------------------------------------------------|:-------------------------------------|
| `path` | [string]: any path to the file or dir             |                                      |
| `from` | [string]: any path preceding before the main path | CWD (current working directory)      | 
|        |                                                   | \_\_DIR\_\_, \_\_NAMESPACE\_\_, etc. |

## 🤝 Contributing

If you have a problem that cannot be solved using this library, please write your solution, and if you want to help 
other developers who also use this library (or if you want to keep your solution working after a new version is 
released, which will be in the package manager dependencies) — create a pull-request. I will be happy to add your 
excellent code to the library!

🐞 Report any bugs or issues you find on the [GitHub issues](https://github.com/MaximGrynykha/winkill/issues).

## 📃 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
