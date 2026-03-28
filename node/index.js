import http from "http";
import fs from "fs";
import jwt from "jsonwebtoken";

const JWT_SECRET = "M@k3_Th1s_S0m3th1ng_RanD0m_AnD_S3cur3_!";

http
  .createServer((req, res) => {
    if (req.method === "GET") {
      res.writeHead(200, { "Content-Type": "text/plain" });
      res.end("Hello Apache!\n");

      return;
    }

    if (req.method === "POST") {
      if (req.url === "/login" || req.url === "/node/login") {
        let body = "";
        req.on("data", (chunk) => {
          body += chunk;
        });
        req.on("end", () => {
          try {
            

            // handle a login attempt
            const parsedBody = JSON.parse(body);
            const username = parsedBody.username;
            const password = parsedBody.password;

            if (!username || !password) {
              res.writeHead(400, { "Content-Type": "application/json" });
              return res.end(JSON.stringify({ error: "Missing username or password" }));
            }

            // open up our "database" (actually a flat file called ./users.txt)
            // to see if there is a username/password combination that matches
            // body.username and body.password
            const fileData = fs.readFileSync("./users.txt", "utf-8");
            const lines = fileData.split("\n");

            let userFound = false;
            let authenticatedUser = null;

            for (const line of lines) {
              if (!line.trim()) continue; // Skip empty lines

              const [id, u, p, r] = line.trim().split(",");

              if (u === username) {
                userFound = true;
                if (p === password) {
                  authenticatedUser = { userId: parseInt(id), role: r };
                  break;
                }
              }
            }
            
            //Return a 404 error if the username isn't found
            if (!userFound) {
              res.writeHead(404, { "Content-Type": "application/json" });
              return res.end(JSON.stringify({ error: `${username} not found` }));
            }


            // return a 401 error if the username is found but the password doesn't match
            if (!authenticatedUser) {
              res.writeHead(401, { "Content-Type": "application/json" });
              return res.end(JSON.stringify({ error: "Invalid password" }));
            }
            // on success, return an encoded userId and role using your JWT_SECRET.
            const payload = {
              userId: authenticatedUser.userId,
              role: authenticatedUser.role,
            };

            const token = jwt.sign(payload, JWT_SECRET, { expiresIn: "1h" });

            res.writeHead(200, { "Content-Type": "application/json" });
            res.end(JSON.stringify({ token: token }));
            // https://www.npmjs.com/package/jsonwebtoken
          } catch (err) {
            console.log(err);
            res.writeHead(500, { "Content-Type": "text/plain" });
            res.end("Server error\n");
          }
        });
      }

      return;
    }

    res.writeHead(404, { "Content-Type": "text/plain" });
    res.end("Not found\n");
  })
  .listen(8000);

console.log("listening on port 8000");
