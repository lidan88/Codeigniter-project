function select_where(option)
{
	var values = option.split(":");
	var type=values[0];
	var id=parseInt(values[1]);
	var is_system=parseInt(values[2]);
	var name=values[3];
	// if not is system then just take them to the library main page.
	
	if(type=="lib")
	{
		if(!is_system)
		{
			top.location = "/admin/library/table_view/"+id+"/";
		}
		else if (is_system) {
			//Its a system item
			
			console.log(name);
			if(name=="Business Impact Analysis")
			{
				top.location="/admin/bia/main/";
			}
			else if (name=="Plans") {
				top.location="/admin/plans/main/";
			}
			else if(name=="Risk Assessment")
			{
				top.location="/admin/risk_assessment/main/";
			}
			else if(name=="Call Chain")
			{
				top.location="/admin/call_chain/main/";
			}
			else if(name=="Reports")
			{
				top.location="/admin/reports/main/";
			}
		}
	
	}
//	alert(values[0] + " "+ values[1]);
}
