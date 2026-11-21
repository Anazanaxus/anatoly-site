#!/usr/bin/env python3
"""Сервер сайта для Timeweb App Platform.

Отдаёт статику из папки и отвечает на /api/<имя> из api/<имя>.json.
Порт берётся из переменной окружения PORT (по умолчанию 8080),
интерфейс 0.0.0.0 — как ожидают PaaS-платформы.
"""
import json
import os
from http.server import HTTPServer, SimpleHTTPRequestHandler

PORT = int(os.environ.get("PORT", "8080"))
ROOT = os.path.dirname(os.path.abspath(__file__))


class SiteHandler(SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=ROOT, **kwargs)

    def _serve_api(self, name):
        if name == "inquiries":
            body = json.dumps({"ok": True}).encode()
            self.send_response(200)
            self.send_header("Content-Type", "application/json; charset=utf-8")
            self.send_header("Content-Length", str(len(body)))
            self.end_headers()
            self.wfile.write(body)
            return
        path = os.path.join(ROOT, "api", name + ".json")
        if not os.path.isfile(path):
            self.send_error(404)
            return
        with open(path, "rb") as f:
            body = f.read()
        self.send_response(200)
        self.send_header("Content-Type", "application/json; charset=utf-8")
        self.send_header("Content-Length", str(len(body)))
        self.end_headers()
        self.wfile.write(body)

    def do_GET(self):
        if self.path.startswith("/api/"):
            self._serve_api(self.path[len("/api/"):].split("?")[0])
        else:
            super().do_GET()

    def do_POST(self):
        if self.path == "/api/inquiries":
            length = int(self.headers.get("Content-Length") or 0)
            if length:
                self.rfile.read(length)
            self._serve_api("inquiries")
        else:
            self.send_error(404)

    def log_message(self, fmt, *args):
        print("%s - %s" % (self.address_string(), fmt % args), flush=True)


if __name__ == "__main__":
    print("Serving on 0.0.0.0:%d" % PORT, flush=True)
    HTTPServer(("0.0.0.0", PORT), SiteHandler).serve_forever()
