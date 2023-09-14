const { app, BrowserWindow, screen } = require("electron");
const { spawn } = require("child_process");
let mainWindow;

function createWindow() {
  // Get primary display dimensions
  const { width, height } = screen.getPrimaryDisplay().workAreaSize;

  mainWindow = new BrowserWindow({
    width: width,
    height: height,
    fullscreen: true,
    webPreferences: {
      webSecurity: false,
    },
  });

  /*const phpServer = spawn(
    "D:/OCTOPUS/OCTOPUS SERVER/OCTOPUS-Server-2.4/php/php.exe",
    ["-S", "localhost:8000"]
  );*/
  mainWindow.loadURL("http://localhost/OCTOPUS-UVSCAN/");
}

app.on("ready", createWindow);
