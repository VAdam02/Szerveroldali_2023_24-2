const { readdir: readDir, readFile, writeFile } = require('fs/promises');

//IIFE - Immediately Invoked Function Expression
(
    () => {
        console.log('hello world');
    }
)();