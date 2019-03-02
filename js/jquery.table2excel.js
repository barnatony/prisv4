//table2excel.js
;(function ( $, window, document, undefined ) {
    var pluginName = "table2excel",

    defaults = {
        exclude: ".noExl",
                name: "Table2Excel"
    };

    // The actual plugin constructor
    function Plugin ( element, options ) {
            this.element = element;
            // jQuery has an extend method which merges the contents of two or
            // more objects, storing the result in the first object. The first object
            // is generally empty as we don't want to alter the default options for
            // future instances of the plugin
            //
            this.settings = $.extend( {}, defaults, options );
            this._defaults = defaults;
            this._name = pluginName;
            this.init();
    }

    Plugin.prototype = {
        init: function () {
            var e = this;

            e.template = {
                head: "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>",
                sheet: {
                    head: "<x:ExcelWorksheet><x:Name>",
                    tail: "</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>"
                },
                mid: "</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>",
                table: {
                    head: "<table>",
                    tail: "</table>"
                },
                foot: "</body></html>"
            };

            e.tableRows = [];

            // get contents of table except for exclude
            $(e.element).each( function(i,o) {
                var tempRows = "";
                $(o).find("tr").not(e.settings.exclude).each(function (i,o) {
                    tempRows += "<tr>" + $(o).html() + "</tr>";
                });
                e.tableRows.push(tempRows);
            });

            e.tableToExcel(e.tableRows, e.settings.name);
        },

        tableToExcel: function (table, name) {
            var e = this, fullTemplate="", i, link, a;

            e.uri = "data:application/vnd.ms-excel;base64,";
            e.base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)));
            };
            e.format = function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                });
            };
            e.ctx = {
                worksheet: name || "Worksheet",
                table: table
            };

            fullTemplate= e.template.head;

            if ( $.isArray(table) ) {
                for (i in table) {
                    //fullTemplate += e.template.sheet.head + "{worksheet" + i + "}" + e.template.sheet.tail;
                    fullTemplate += e.template.sheet.head + "Table" + i + "" + e.template.sheet.tail;
                }
            }

            fullTemplate += e.template.mid;

            if ( $.isArray(table) ) {
                for (i in table) {
                    fullTemplate += e.template.table.head + "{table" + i + "}" + e.template.table.tail;
                }
            }

            fullTemplate += e.template.foot;

            for (i in table) {
                e.ctx["table" + i] = table[i];
            }
            delete e.ctx.table;

            if (typeof msie !== "undefined" && msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
            {
                if (typeof Blob !== "undefined") {
                    //use blobs if we can
                    fullTemplate = [fullTemplate];
                    //convert to array
                    var blob1 = new Blob(fullTemplate, { type: "text/html" });
                    window.navigator.msSaveBlob(blob1, getFileName(e.settings) );
                } else {
                    //otherwise use the iframe and save
                    //requires a blank iframe on page called txtArea1
                    txtArea1.document.open("text/html", "replace");
                    txtArea1.document.write(e.format(fullTemplate, e.ctx));
                    txtArea1.document.close();
                    txtArea1.focus();
                    sa = txtArea1.document.execCommand("SaveAs", true, getFileName(e.settings) );
                }
            } else {
            	console.log(e.format(fullTemplate, e.ctx));
                link = e.uri + e.base64(e.format(fullTemplate, e.ctx));
                a = document.createElement("a");
                a.download = getFileName(e.settings);
                a.href = link;
                document.body.appendChild(a);

                a.click();

                document.body.removeChild(a);
            }

            return true;
        }
    };

    function getFileName(settings) {
        return ( settings.filename ? settings.filename : "table2excel") + ".xls";
    }

    $.fn[ pluginName ] = function ( options ) {
        var e = this;
            e.each(function() {
                if ( !$.data( e, "plugin_" + pluginName ) ) {
                    $.data( e, "plugin_" + pluginName, new Plugin( this, options ) );
                }
            });

        // chain jQuery functions
        return e;
    };

})( jQuery, window, document );



var tablesToExcel = (function() {
	 var uri = 'data:application/vnd.ms-excel;base64,'
		    , 
tmplWorkbookXML = '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
		     +"<Styles>" +
		     "<Style ss:ID='xls-tableCaption' ss:Name='xls-tableCaption'><Alignment ss:MergeCells='5' ss:Vertical='Bottom' ss:Horizontal='Left'/><Font ss:Bold='1' /></Style>" +
	     		"<Style ss:ID='xls-tableHeader' ss:Name='xls-tableHeader'><Alignment ss:Vertical='Bottom' ss:Horizontal='Center'/><Borders><Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/></Borders><Font ss:Color='#FFFFFF' ss:Bold='1' /><Interior ss:Color='#41cac0' ss:Pattern='Solid'/></Style>" +
		     		"<Style ss:ID='xls-oddRow' ss:Name='xls-oddRow'><Alignment ss:Vertical='Bottom' ss:Horizontal='Right'/><Borders><Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/></Borders><Font ss:Color='#184A47' /><Interior ss:Color='#E0F9FF' ss:Pattern='Solid'/></Style>" +
		     		"<Style ss:ID='xls-rightOddRow' ss:Name='xls-rightOddRow'><Alignment ss:Vertical='Bottom' ss:Horizontal='Left'/><Borders><Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/></Borders><Font ss:Color='#184A47' /><Interior ss:Color='#E0F9FF' ss:Pattern='Solid'/></Style>" +
		     		"<Style ss:ID='xls-evenRow' ss:Name='xls-evenRow'><Alignment ss:Vertical='Bottom' ss:Horizontal='Right'/><Borders><Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/></Borders><Font ss:Color='#2A827C' /><Interior ss:Color='#FFFFFF' ss:Pattern='Solid'/></Style>"+
		     		"<Style ss:ID='xls-rightEvenRow' ss:Name='xls-rightEvenRow'><Alignment ss:Vertical='Bottom' ss:Horizontal='Left'/><Borders><Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/><Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1' ss:Color='#DDDDDD'/></Borders><Font ss:Color='#2A827C' /><Interior ss:Color='#FFFFFF' ss:Pattern='Solid'/></Style></Styles>"
				    + '{worksheets}</Workbook>'
		    , tmplWorksheetXML = '<Worksheet ss:Name="{nameWS}"><Table>{rows}</Table></Worksheet>'
		    , tmplCellXML = '<Cell{attributeStyleID}><Data ss:Type="{nameType}">{data}</Data></Cell>'
  , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
  , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(tables, wsnames, wbname, appname) {
    var ctx = "";
    var workbookXML = "";
    var worksheetsXML = "";
    var rowsXML = "";
    for (var i = 0; i < tables.length; i++) {
  	if (!tables[i].nodeType) tables[i] = document.getElementById(tables[i]);
      for (var j = 0; j < tables[i].rows.length; j++) {
    	 rowsXML += '<Row>'
        for (var k = 0; k < tables[i].rows[j].cells.length; k++) {
        	/* var dataType = tables[i].rows[j].cells[k].getAttribute("data-type");//Add Feature like color,format,for excel
             var dataStyle = tables[i].rows[j].cells[k].getAttribute("data-style");*/
             var dataValue = tables[i].rows[j].cells[k].getAttribute("data-value");
             dataValue = (dataValue)?dataValue:tables[i].rows[j].cells[k].innerHTML;
            // var dataFormula = tables[i].rows[j].cells[k].getAttribute("data-formula");
            // dataFormula = (dataFormula)?dataFormula:(appname=='Calc' && dataType=='DateTime')?dataValue:null;
   if(j==0){
	    ctx = {attributeStyleID:"   ss:StyleID='xls-tableCaption'",nameType:'String',data:dataValue};
	}else if(j==1){
	    ctx = {attributeStyleID:" ss:StyleID='xls-tableHeader'",nameType:'String',data:dataValue};
	}else{
		dataValue=dataValue.replace(/,|_/g,'');
			if(j%2==0){
        		if(isNaN(Number(dataValue))==0){
        			dataValue=Number(dataValue);
        			ctxAtrval= " ss:StyleID='xls-oddRow' ";tdDatatype="Number";
        		}else{
        			ctxAtrval= " ss:StyleID='xls-rightOddRow' ";tdDatatype="String";
        		}
        	}else{
        		if(isNaN(Number(dataValue))==0){
        			dataValue=Number(dataValue);
        			ctxAtrval= " ss:StyleID='xls-evenRow' ";tdDatatype="Number";
        		}else{
        			ctxAtrval= " ss:StyleID='xls-rightEvenRow' ";tdDatatype="String";
        		}
        	}
		ctx = {nameType: tdDatatype,attributeStyleID:ctxAtrval,data:dataValue};
        }
          rowsXML += format(tmplCellXML, ctx);
         }
        rowsXML += '</Row>';
      }
      ctx = {rows: rowsXML, nameWS: wsnames[i] || 'Sheet' + i};
      worksheetsXML += format(tmplWorksheetXML, ctx);
      rowsXML = "";
    }
  
    ctx = {created: (new Date()).getTime(), worksheets: worksheetsXML};
    workbookXML = format(tmplWorkbookXML, ctx);
    
    console.log(worksheetsXML);
    
    var link = document.createElement("A");
    link.href = uri + base64(workbookXML);
    link.download = wbname || 'Workbook.xls';
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
})();