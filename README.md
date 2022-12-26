# Api

# Verbs
curl http://127.0.0.1/api/index.php/tasks
curl http://127.0.0.1/api/index.php/tasks/2
curl -X POST -F 'name=linuxize' http://127.0.0.1/api/index.php/tasks
curl -X DELETE http://127.0.0.1/api/index.php/tasks/8
curl -X DELETE http://127.0.0.1/api/index.php/tasks/8

// Excepting a json response from the server
curl -X DELETE http://127.0.0.1/api/index.php/tasks/7 -H "Accept: application/json"


### Specifying the Content-Type
curl -X POST -H "Content-Type: application/json" \
    -d '{"name": "linuxize", "email": "linuxize@example.com"}' \
    http://127.0.0.1/api/index.php/contact

### Uploading Files
curl -X POST -F 'image=@/home/user/Pictures/wallpaper.jpg' http://127.0.0.1/api/index.php/upload