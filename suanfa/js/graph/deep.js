function Graph(v) {
    this.vertices = v;
    this.edges = 0;
    this.adj = [];
    for (var i = 0; i < this.vertices; ++i) {
        this.adj[i] = [];
        // this.adj[i].push("");
    }
    this.addEdge = addEdge;
    this.showGraph = showGraph;
    this.toString = toString;
    this.dfs = dfs;
    // this.bfs = bfs;
    this.marked = [];
    for (var i = 0; i < this.vertices; ++i) {
        this.marked[i] = false;
    }
}

function addEdge(v, w) {
    this.adj[v].push(w);
    this.adj[w].push(v);
    this.edges++;
}

// function showGraph() {
//     for (var i = 0; i < this.vertices; ++i) {
//         putstr(i + " -> ");
//         for (var j = 0; j < this.vertices; ++j) {
//             if (this.adj[i][j] != undefined)
//                 putstr(this.add[i][j] + ' ');
//         }
//         print();
//     }
// }
function showGraph() {
    for (var i = 0; i < this.adj.length; ++i) {
        var str="";
        for (var j = 0; j < this.adj.length; ++j) {
            if (this.adj[i][j] && this.adj[i][j] != undefined)
                str+=this.adj[i][j] + ' ';
        }
        console.log(i + "->"+str);
    }
}

function dfs(v) {
    this.marked[v] = true;
    if (this.adj[v] != undefined) {
        console.log("Visited vertex: " + v);
    }
    for(var w of this.adj[v]) {
        if (!this.marked[w]) {
            this.dfs(w);
        }
    }
}




g = new Graph(5);
g.addEdge(0, 1);
g.addEdge(0,2);
g.addEdge(1,3);
g.addEdge(2,4);
g.showGraph();
g.dfs(0);
// console.log('bfs');
// g.bfs(0);