<!DOCTYPE html>
<meta charset="utf-8">
<style>

    .node {
        stroke: #fff;
        stroke-width: 1.5px;
    }

    .link {
        stroke: #999;
        stroke-opacity: .6;
    }

</style>
<body></body>
<script src="http://d3js.org/d3.v2.min.js?2.9.6"></script>
<script>
    var width = 2000,
        height = 1000;

    var color = d3.scale.category20();

    var force = d3.layout.force()
        .charge(-100)
        .linkDistance(10)
        .size([width, height]);

    var svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height);

    d3.json("json.php", function (graph) {
        force
            .nodes(graph.nodes)
            .links(graph.links)
            .start();

        var link = svg.selectAll(".link")
            .data(graph.links)
            .enter().append("line")
            .attr("class", "link")
            .style("stroke-width", function (d) {
                return Math.sqrt(d.value);
            });

        var gnodes = svg.selectAll('g.gnode')
            .data(graph.nodes)
            .enter()
            .append('g')
            .classed('gnode', true);

        var node = gnodes.append("circle")
            .attr("class", "node")
            .attr("r", 10)
            .style("fill", function (d) {
                return color(d.group);
            })
            .call(force.drag);

        var labels = gnodes.append("text")
            .text(function (d) {
                return d.name;
            });

        console.log(labels);

        force.on("tick", function () {
            link.attr("x1", function (d) {
                    return d.source.x;
                })
                .attr("y1", function (d) {
                    return d.source.y;
                })
                .attr("x2", function (d) {
                    return d.target.x;
                })
                .attr("y2", function (d) {
                    return d.target.y;
                });

            gnodes.attr("transform", function (d) {
                return 'translate(' + [d.x, d.y] + ')';
            });


        });
    });

</script>