<?php  
	function connect() {
		// Create connection
		$mysqli_connection = new MySQLi('vergil.u.washington.edu', 'root', 'ernie', 'ordering_system', 8685);
		return $mysqli_connection;
	}
    function export_excel_csv()
    {
        $conn = connect();
        $sql = "SELECT * FROM pizza_order ORDER BY order_num";
        $rec = $conn->query($sql);
        $num_fields = $rec->field_count;
       
        /* Get field information for all columns */
        $finfo = $rec->fetch_fields();

        foreach ($finfo as $val) {
            $header .= ($val->name)."\\t";
        }
       
        while($row = mysqli_fetch_row($rec))
        {
            $line = '';
            foreach($row as $value)
            {          
                                                
                if((!isset($value)) || ($value == ""))
                {
                    $value = "\\t";
                }
                else
                {
                    $value = str_replace( '"' , '""' , $value );
                    $value = '"' . $value . '"' . "\\t";
                }
                $line .= $value;
            }
            $data .= trim( $line ) . "\\n";
        }
        
        $data = str_replace("\\r" , "" , $data);
       
        if ($data == "")
        {
            $data = "\\n No Record Found!\n";                       
        }
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=reports.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        print "$header\\n$data";
        
    }
    export_excel_csv();
?>
