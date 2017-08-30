# AhocorasickPHP
======================================

> Ahocorasick Implementation with Tree Traversal using BFS, for PHP5 and PHP7

# Usage

## Installing 

Add to composer.json:

```PHP
"require": {
	"shivamdawer/ahocorasick-php": "dev-master" // or, 1.0
},
"repositories": [
    {
        "type": "vcs",
        "no-api": true,
        "url": "git@github.com:shivamdawer/ahocorasick-php.git"
    }
]
```

Update composer

```bash
$ composer update
```

```PHP
use Ahocorasick\Src\AhocorasickTest;

public function __construct(AhocorasickTest $ahocorasickTest)
{
    $this->ahocorasickTest = $ahocorasickTest;
}
```



## API


#### search()

Search keywords and positions per string. <br>
Arguments :
1. $textArray    - Array of strings
2. $keywords     - Dictionary of keywords to search
3. $keywordsName - Filename to use for cache created from dictionary
4. $useCache     - To use cache or not (optional)
5. $delimiters   - Array of valid delimiters at boundaries of searched keywowrds (optional)
6. $sortByLength - Sort searched keywords by length : 'asc', 'desc', or false (optional)
7. $trimText     - Trim input strings before search (optional)

**Usage**
```PHP
$searchResults = $this->ahocorasickTest->search(["This is sentence 1","Another sentence"], ["sentence","This"], "sentences_set", true, [" ",","], "desc", true);
```
**Output**
```
$matchResults = [['value' => 'sentence', 'len' => 8, 'start_pos' => 8, 'end_pos' => 15], ['value' => 'This', 'len' => 4, 'start_pos' => 0, 'end_pos' => 3]], 
					['value' => 'sentence', 'len' => 8, 'start_pos' => 8, 'end_pos' => 15]];
var_dump($searchResults == $matchResults); // Returns true
```

#### deleteCache()

Deletes cache file for dictionary

**Usage**
```PHP
$ipfs->ahocorasickTest->deleteCache("sentences_set");
```

# License 

MIT License

Copyright (c) 2017 Shivam Dawer

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
