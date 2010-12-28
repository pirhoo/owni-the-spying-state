<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>The Spying State</title>

        <script type="text/javascript" src="./Protovis/protovis-d3.2.js"></script>
        <script src="svgweb/src/svg.js" data-path="svgweb/src/" type="text/javascript"></script>

        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
              google.load("jquery", "1.4.2");
              google.load("jqueryui", "1.8.2");
        </script>

        <script type="text/javascript" src="./global.js"></script>
        <script type="text/javascript" src="./data.js"></script>

        <link type="text/css" href="./themes/base/jquery.ui.all.css" rel="Stylesheet" />
        <link type="text/css" href="./themes/global.css" rel="Stylesheet" />

    </head>
    <body>

        <div id="app">
            <div id="workspace">
                <div id="layout">



                    <script type="image/svg+xml" id="ie-render">
                        <svg></svg>
                    </script>

                    <script type="text/javascript+protovis">

                            var lib = 500;
                            var ite = 4000;
                            
                            
                            var vis = new pv.Panel()
                                .width( 800 )
                                .height( 600 )
                                .event("mousewheel", pv.Behavior.zoom());

                            var force = vis
                                .add(pv.Layout.Force)
                                .bound(true)
                                .springLength(180)
                                .iterations( function () { return ite; })
                                .nodes(function () { return data.nodes; })
                                .links(function () { return data.links; });

                            //force.link.add(pv.Line).interpolate("basis").segmented(true).lineWidth(3).strokeStyle(function (d)  { return colors(d.group); });
                   
                            force.node
                                .add(pv.Dot)
                                    .visible(true)
                                    .cursor("move")
                                    .size(function(d) { return (d.group == 0) ? 25000:20; })
                                    .fillStyle(function(d)   { return this.index == 0 ? "white" : "#666666" })
                                    .strokeStyle(function()  { return this.fillStyle().darker() })
                                    .lineWidth(1)
                                    .event("mousedown", pv.Behavior.drag() )
                                    .event("drag", force)
                                .anchor("top").add(pv.Label)
                                    .transform({x: -500})
                                    .text( function (d) { return (this.index > 0) ? d.label : ""; })
                                    .font(function(d) { return "bold 12px sans-serif"; })
                                    .textStyle(function (d)  { return "#666666"; })

                                .segmented(true)
                                .add(pv.Dot)
                                    .event("mousedown", pv.Behavior.drag() )
                                    .event("drag", force)
                                    .lineWidth(0)
                                    .fillStyle("#ff3000")
                                    .strokeStyle(null)
                                    .top(function (d) { return d.y; })
                                    .visible(function (d) { return this.index == 0 ? true : false; })
                                    .size(function () { return lib * 10;  })
                                    .anchor("center").add(pv.Label).text("MES LIBÉRTÉS")
                                    .textStyle("white")
                                    .font(function(d) { return "12px sans-serif"; });

                            vis.render();
                            ite = null;
                            
                            force.reset();
                            vis.render();

                    </script>
                </div>

                <!--[if IE]>
                <![endif]-->
                <!--[if !IE]>-->
                <!--<![endif]-->

                <div id="slider">
                    <div id="year-label" class="labels"></div>
                    <div class="labels" style="float:right; text-align:right">2010</div>
                    <div class="labels">2001</div>
                    <div id="range" style="margin:10px 0px;"></div>
                </div>
            </div>

            
        </div>

        <!--
        <object data="data.svg" width="900" height="600"
        type="image/svg+xml"
        codebase="http://www.adobe.com/svg/viewer/install/" />
            -->
    </body>
</html>
