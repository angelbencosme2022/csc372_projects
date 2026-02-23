// ===== LOAD MODULES =====
const http = require('http');
const fs = require('fs');
const path = require('path');

// ===== CONFIGURATION =====
const PORT = 3000;

// ===== CONTENT TYPE MAP =====
// Maps file extensions to MIME types
const contentTypes = {
    '.html': 'text/html',
    '.css':  'text/css',
    '.js':   'application/javascript',
    '.json': 'application/json',
    '.png':  'image/png',
    '.jpg':  'image/jpeg',
    '.jpeg': 'image/jpeg',
    '.gif':  'image/gif',
    '.svg':  'image/svg+xml',
    '.ico':  'image/x-icon',
    '.txt':  'text/plain',
};

// ===== SERVE STATIC FILE FUNCTION =====
/**
 * Reads the file at the given filePath and sends it as an HTTP response.
 * - Status 200 on success
 * - Status 500 on server/read error
 * Sets the correct Content-Type header based on the file extension.
 *
 * @param {http.ServerResponse} res       - The HTTP response object
 * @param {string}              filePath  - Absolute path to the file to serve
 * @param {string}              [ext]     - Optional file extension override
 */
function serveStaticFile(res, filePath, ext) {
    // Determine extension from filePath if not provided
    const fileExt = ext || path.extname(filePath).toLowerCase();
    const contentType = contentTypes[fileExt] || 'application/octet-stream';

    fs.readFile(filePath, function (err, data) {
        if (err) {
            // Server error while reading the file
            console.error(`Error reading file ${filePath}:`, err.message);
            res.writeHead(500, { 'Content-Type': 'text/html' });
            res.end('<h1>500 - Internal Server Error</h1>');
            return;
        }

        // Success - send the file
        res.writeHead(200, { 'Content-Type': contentType });
        res.end(data);
    });
}

// ===== URL → FILE PATH MAP =====
// Maps clean URL paths to files inside the public folder
const routes = {
    '/':         'public/index.html',
    '/index':    'public/index.html',
    '/shop':     'public/shop.html',
    '/about':    'public/about.html',
    '/contact':  'public/contact.html',
    '/checkout': 'public/checkout.html',
};

// ===== CREATE SERVER =====
const server = http.createServer(function (req, res) {

    // --- Normalize the URL ---
    // 1. Strip query string  (e.g. /page?foo=bar  →  /page)
    let urlPath = req.url.split('?')[0];

    // 2. Convert to lowercase
    urlPath = urlPath.toLowerCase();

    // 3. Remove trailing slash (unless it IS the root "/")
    if (urlPath.length > 1 && urlPath.endsWith('/')) {
        urlPath = urlPath.slice(0, -1);
    }

    console.log(`[${new Date().toISOString()}] ${req.method} ${urlPath}`);

    // --- Route: named HTML pages ---
    if (routes[urlPath]) {
        const filePath = path.join(__dirname, routes[urlPath]);
        // Check the file actually exists before trying to serve it
        fs.access(filePath, fs.constants.F_OK, function (err) {
            if (err) {
                serve404(res);
            } else {
                serveStaticFile(res, filePath);
            }
        });
        return;
    }

    // --- Route: static assets (css, js, images, etc.) ---
    // Any path that still has an extension is treated as a static asset
    const ext = path.extname(urlPath).toLowerCase();
    if (ext) {
        const filePath = path.join(__dirname, 'public', urlPath);
        fs.access(filePath, fs.constants.F_OK, function (err) {
            if (err) {
                serve404(res);
            } else {
                serveStaticFile(res, filePath, ext);
            }
        });
        return;
    }

    // --- Fallback: nothing matched → 404 ---
    serve404(res);
});

// ===== 404 HELPER =====
/**
 * Serves the custom 404 page with HTTP status 404.
 * @param {http.ServerResponse} res
 */
function serve404(res) {
    const notFoundPath = path.join(__dirname, 'public', '404.html');
    fs.readFile(notFoundPath, function (err, data) {
        res.writeHead(404, { 'Content-Type': 'text/html' });
        if (err) {
            // Fallback plain-text 404 if the custom page can't be read
            res.end('<h1>404 - Page Not Found</h1><p><a href="/">Go Home</a></p>');
        } else {
            res.end(data);
        }
    });
}

// ===== START LISTENING =====
server.listen(PORT, function () {
    console.log(`401 Thrift server is running!`);
    console.log(`Visit: http://localhost:${PORT}`);
});