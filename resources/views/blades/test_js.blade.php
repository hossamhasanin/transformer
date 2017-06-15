<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
    {{ Html::Style("bootstrap/css/bootstrap.css") }}
</head>
<body>

<div class="sort_con" style="width: 200px;height: 200px">
<div class="comp">
    <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Brand</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
  </div>
</div>
<div class="sort_conf" style="height: 200px;background: #EEE;width: 400px;"></div>





  {{ Html::Script("plugins/jQuery/jquery-2.2.3.min.js") }}
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
{{ Html::Script("/js/interact.min.js") }}
<script type="text/javascript">
	// function dragMoveListener (event) {
 //    var target = event.target,
 //        // keep the dragged position in the data-x/data-y attributes
 //        x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
 //        y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

 //    // translate the element
 //    target.style.webkitTransform =
 //    target.style.transform =
 //      'translate(' + x + 'px, ' + y + 'px)';

 //    // update the posiion attributes
 //    target.setAttribute('data-x', x);
 //    target.setAttribute('data-y', y);
 //  }

	// interact('.resize-drag')
 //  .draggable({
 //    onmove: window.dragMoveListener
 //  })
 //  .resizable({
 //    preserveAspectRatio: false,
 //    edges: { left: false, right: true, bottom: true, top: false },
 //    restrict: {
 //      restriction: "parent",
 //      endOnly: true,
 //    }
 //  })
 //  .on('resizemove', function (event) {
 //    var target = event.target,
 //        x = (parseFloat(target.getAttribute('data-x')) || 0),
 //        y = (parseFloat(target.getAttribute('data-y')) || 0);

 //    // update the element's style
 // target.style.width  = event.rect.width + 'px';
 //    target.style.height = event.rect.height + 'px';

	// console.log(event)
 //    // translate when resizing from top or left edges
 //    x += event.deltaRect.left;
 //    y += event.deltaRect.top;

 //    target.style.webkitTransform = target.style.transform =
 //        'translate(' + x + 'px,' + y + 'px)';

 //    target.setAttribute('data-x', x);
 //    target.setAttribute('data-y', y);
 //    target.textContent = Math.round(event.rect.width) + '×' + Math.round(event.rect.height);
 //  });
</script>
<script type="text/javascript">
  $(".sort_con").sortable({
    connectWith: ".sort_conf" , 
    receive: function(event , ui){
        console.log($(ui.item).attr("id"))
    } ,
    start:function(){
      $(".sort_conf").css({
        "border" : "solid 2px"
      })
    } , 
    stop:function(){
      $(".sort_conf").css({
        "border" : "none"
      })
    }
  });
  $(".sort_conf").sortable({
    connectWith: ".sort_con" , 
    receive: function(event , ui){
        console.log(ui.item)
        //$(ui.item).width(this.width)
    },
    start:function(){
      $(".sort_conf").css({
        "border" : "solid 2px"
      })
    } , 
    stop:function(){
      $(".sort_conf").css({
        "border" : "none"
      })
    }
  });
</script>

</body>
</html>