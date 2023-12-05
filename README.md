### INXY Test

## install
Clone repo: <code>git clone git@github.com:zak956/test_inxy.git</code>\
Go to directory: <code>cd test_inxy</code>\
Composer: <code>./start.sh</code>\
Tests: <code>cd laradock && docker-compose exec workspace vendor/bin/phpunit</code>\

<code>POST /api/cube/create</code> - create cube to initial state\
<code>GET /api/cube/{cube_id}/rotate/{front|top|left|right|back|bottom}/{cw|ccw}</code> - rotate selected face in selected direction\
<code>GET /api/cube/{cube_id}/shuffle</code> - randomly shuffle cube\
<code>GET /api/cube/{cube_id}/init</code> - reset cube to initial state\
<code>GET /api/cube/{cube_id}</code> - get cube\

return json example:
<code>{"data":{"id":1,"state":{"front":[["B","B","B"],["B","B","B"],["B","B","B"]],"left":[["O","O","O"],["O","O","O"],["O","O","O"]],"right":[["W","W","W"],["W","W","W"],["W","W","W"]],"back":[["R","R","R"],["R","R","R"],["R","R","R"]],"top":[["G","G","G"],["G","G","G"],["G","G","G"]],"bottom":[["Y","Y","Y"],["Y","Y","Y"],["Y","Y","Y"]]}}}</code>
