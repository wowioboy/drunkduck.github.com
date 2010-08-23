<script>
//Ext.onReady(function(){
//	var store = new Ext.data.JsonStore({
//        root: 'topics',
//        totalProperty: 'totalCount',
//        idProperty: 'threadid',
//        remoteSort: true,
//
//        fields: [
//            'title', 'forumtitle', 'forumid', 'author',
//            {name: 'replycount', type: 'int'},
//            {name: 'lastpost', mapping: 'lastpost', type: 'date', dateFormat: 'timestamp'},
//            'lastposter', 'excerpt'
//        ],
//
//        // load using script tags for cross domain, if the data in on the same domain as
//        // this page, an HttpProxy would be better
//        proxy: new Ext.data.ScriptTagProxy({
//            url: 'http://extjs.com/forum/topics-browse-remote.php'
//        })
//    });
//	store.setDefaultSort('lastpost', 'desc');
//	var grid = new Ext.grid.GridPanel({
//        width:700,
//        height:500,
//        renderTo: 'grid',
//        title:'ExtJS.com - Browse Forums',
//        store: store,
//        trackMouseOver:false,
//        disableSelection:true,
//        loadMask: true,
//
//        // grid columns
//        columns:[{
//            id: 'topic', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
//            header: "Topic",
//            dataIndex: 'title',
//            width: 420,
////            renderer: renderTopic,
//            sortable: true
//        },{
//            header: "Author",
//            dataIndex: 'author',
//            width: 100,
//            hidden: true,
//            sortable: true
//        },{
//            header: "Replies",
//            dataIndex: 'replycount',
//            width: 70,
//            align: 'right',
//            sortable: true
//        },{
//            id: 'last',
//            header: "Last Post",
//            dataIndex: 'lastpost',
//            width: 150,
////            renderer: renderLast,
//            sortable: true
//        }],
//
//        // customize view config
//        viewConfig: {
//            forceFit:true,
//            enableRowBody:true,
//            showPreview:true,
//            getRowClass : function(record, rowIndex, p, store){
//                if(this.showPreview){
//                    p.body = '<p>'+record.data.excerpt+'</p>';
//                    return 'x-grid3-row-expanded';
//                }
//                return 'x-grid3-row-collapsed';
//            }
//        },
//
//        // paging bar on the bottom
////        bbar: new Ext.PagingToolbar({
////            pageSize: 25,
////            store: store,
////            displayInfo: true,
////            displayMsg: 'Displaying topics {0} - {1} of {2}',
////            emptyMsg: "No topics to display",
////            items:[
////                '-', {
////                pressed: true,
////                enableToggle:true,
////                text: 'Show Preview',
////                cls: 'x-btn-text-icon details',
////                toggleHandler: function(btn, pressed){
////                    var view = grid.getView();
////                    view.showPreview = pressed;
////                    view.refresh();
////                }
////            }]
////        })
//    });
//
//    // render it
////    grid.render('topic-grid');
//
//    // trigger the data store load
//    store.load({params:{start:0, limit:25}});
//});
</script>
<!--<div id="grid"></div>-->
<DIV STYLE='WIDTH:810px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Created Comics</DIV>
<?


$BIGGEST_MONTH = 0;
$MONTH_STATS   = array();

$res = db_query("SELECT created_timestamp FROM comics");
while( $row = db_fetch_object($res) )
{
  $MONTH_STATS[ date("Y-m", $row->created_timestamp) ]++;
}

foreach($MONTH_STATS as $key=>$value ) {
  if ( $value > $BIGGEST_MONTH ) {
    $BIGGEST_MONTH = $value;
  }
}


krsort($MONTH_STATS);

?><table border='0' cellpadding='0' cellspacing='0' width='800'><?


foreach($MONTH_STATS as $key=>$value )
{
  ?>
  <tr>
   <td align='left' width='100'><b><?=$key?></b></td>
   <td align='left' width='600'><div align='right' style='color:white;background:#0000ff;width:<?=( ($value/$BIGGEST_MONTH)*600 )?>px'>&nbsp;</div></td>
   <td align='left' width='100'><?=number_format($value)?></td>
  </tr>
  <?
}


?></table><?

?>