colorOn = '#A9F5BC'
colorOff = '#396d93'
colorClick = '#bf2e23'
	
//Goals the edos
edos = ['AGU','BCN','BCS','CAM','CHH',
		'CHP','COA','COL','DIF','DUR',		
		'GRO','GUA','HID','JAL','MEX',		
		'MIC','MOR','NAY','NLE','OAX',
		'PUE','QUE','ROO','SIN','SLP',
		'SON','TAB','TAM','TLA','VER',
		'YUC','ZAC','GUA'
		]
edos_names = [	'<strong>Aguascalientes</strong><br />Tel. 01 (449) 012-7349<br />Tel. 01 (449) 914-8035<br /><br />Ing. Miguel Angel Godinez<br />Nextel. 72*135481*1<br /><br />Ing. Gerardo Godinez<br />Cel. 045 (449) 122-4140<br />Nextel. 72*135481*6<br />',
              	'Baja California',
              	'Baja California Sur<br />a',
              	'Campeche',
              	'Chihuahua',
				'Chiapas',
				'Coahuila',
				'Colima',
				'Distrito Federal',
				'Durango',
				'Guerrero',
				'Guanajuato',
				'Hidalgo',
				'Jalisco',
				'México',
				'Michoacán',
				'Morelos',
				'Nayarit',
				'Nuevo León',
				'Oaxaca',
				'Puebla',
				'Querétaro',
				'Quintana Roo',
				'Sinaloa',
				'San Luis Potosí',
				'Sonora',
				'Tabasco',
				'Tamaulipas',
				'Tlaxcala',
				'Veracruz',
				'Yucatán',
				'Zacatecas',
				'Guatemala'
			]
var a = $("mx_map")

a.addEventListener("load",function(){
	
	var svgDoc = a.contentDocument;
	var actual;
	
	for(i=0; i< edos.length; i++){

		edo = svgDoc.getElementById(edos[i])
		edo.style.setProperty("cursor", "pointer", "")
		fillColor(edo,colorOff)
		
		//on mouse over
		edo.addEventListener("mouseover",function(){
			edos.each(function(e){
				if( e != edos[i] && e != actual )
					fillColor(svgDoc.getElementById( e ),colorOff)
			});
			if( this.id != actual )
				fillColor(this,colorOn)	
		},false);
		
		
		edo.addEventListener("click",function(){
			$('edo').innerHTML = edos_names[edos.indexOf(this.id) ]
			actual = this.id;
			fillColor(svgDoc.getElementById(this.id) , colorClick)
		},false);
	}
},false);

function fillColor(e,c){
	if(e.tagName != 'g' ){
		e.style.setProperty("fill", c, "")
		return
	}
	paths = e.getElementsByTagName('path')
	for(j=0; j< paths.length; j++)
		paths[j].style.setProperty("fill", c, "")
}