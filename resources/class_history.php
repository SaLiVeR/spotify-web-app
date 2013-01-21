<?php

CLASS HISTORY {

    private $Tables = array(
        'recent' => array(
            'columns' => array(
                'Track' => 'ti.title',
                'Artist' => 'ti.artist',
                'Duration' => 'ti.duration',
                'Album' => 'ti.Album',
                'Votes' => 'SUM(IF(v.updown, 1, -1))',
                'PlayCount' => 'something',
                'ChooserID' => 'h.addedBy',
                'Chooser' => 'u.Username',
                'Played' => 'h.datePlayed',
                'Added' => 'h.dateAdded'
            ),
            'tables' => array(
                'history AS h',
                'JOIN track_info AS ti ON h.trackid = ti.trackid',
                'LEFT JOIN users AS u ON h.addedBy = u.ID'
            ),
            'order' => 'ORDER BY h.datePlayed DESC',
            'group' => 'GROUP BY h.trackid'
            
        ),
        'popular' => array(),
        'popartist' => array(),
        'popuser' => array()
    );
    //Configuration of the columns. Whether not they need a new column, and their label
    private $Columns = array(
        'Track' => array('label' => 'Track', 'column' => true),
        'Artist' => array('label' => 'Artist', 'column' => true),
        'Duration' => array('label' => 'Duration', 'column' => true),
        'Album' => array('label' => 'Album', 'column' => true),
        'Votes' => array('label' => 'Score', 'column' => true),
        'PlayCount' => array('label' => 'Play Count', 'column' => true),
        'ChooserID' => array('label' => 'Username', 'column' => true),
        'Chooser' => array('label' => '', 'column' => false),
        'Played' => array('label' => 'played', 'column' => true),
        'Added' =>  array('label' => 'Time before Played', 'column' => true)
    );
    
    private $TableType;
    private $Output;
    
    private function build_query() {
        $Cols = array();
        foreach($this->Tables[$this->TableType]['columns'] as $Column => $ColQuery) {
            $Cols[] = $ColQuery . ' AS ' . $Column;
        }
        $Query = "SELECT " . implode(', ', $Cols) . " FROM " . implode(', ', $this->Tables[$this->TableType]['tables']);
        if(array_key_exists('order', $this->Tables[$this->TableType])) $Query += $this->Tables[$this->TableType]['order'];
        if(array_key_exists('group', $this->Tables[$this->TableType])) $Query += $this->Tables[$this->TableType]['group'];
        
        return $Query;
    }

    function createTable($Table) {
        global $DB;
        
        if(!in_array($Table, array_keys($this->Tables))) $this->error('Table does not exist');
        $this->TableType = $Table;
        
        $DB->query($this->build_query());
        $Data = $DB->to_array(false, MYSQLI_ASSOC);        
        
        $this->build_table_header();
        $this->add_data($Data);
        $this->end_table();
        return $this->Output;
    }
    
    private function build_table_header() {
        $this->Output = '
            <table id="history-table-' . $this->TableType . '">
                <thead>
                    <tr>';
        foreach($this->Tables[$this->TableType] as $Col => $CQ) {
            if($this->Columns[$Col]['column']) {
                $this->Output += '<th class="' . strtolower($Col) . '">' . $this->Columns[$CQ]['label'] . '</th>';
            }
        }
        $this->Output += '
                    </tr>
                </thead>
                <tbody>';
        
    }
    
    private function add_data($Data) {
        $a = 'even';
        foreach($Data as $D) {
            $a = ($a == 'even') ? 'odd' : 'even';
            $this->Output += "<tr class='" . $a . "'>";
            foreach($D as $Col=>$Val) {
                switch($Col) {
                    default:
                        echo "<td>" . $Val . "</td>";
                }
            }
        }
    }
    
    private function end_table() {
        $this->Output += "</tbody></table>";
    }
    
    private function error($E) {
        die($E);
    }
}

?>