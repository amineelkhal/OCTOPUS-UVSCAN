const { app, BrowserWindow } = require('electron');
const { spawn } = require('child_process');
let mainWindow;

function createWindow() {
    mainWindow = new BrowserWindow({
        width: 800,
        height: 600,
        webPreferences: {
            nodeIntegration: true
        }
    });

    const phpServer = spawn('D:/OCTOPUS/OCTOPUS SERVER/OCTOPUS-Server-2.4/php/php.exe', ['-S', 'localhost:8000']);
    mainWindow.loadURL('http://localhost:8000');
}

app.on('ready', createWindow);
