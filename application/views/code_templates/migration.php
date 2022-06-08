<?php echo '<?php 

defined("BASEPATH") OR exit("No direct script access allowed");'; ?>


class Migration_Add_<?php echo $table_name ?> extends CI_Migration 
{

    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up() 
    {
<?php foreach ($fields as $key => $value): ?>
        <?php echo $value ?>

<?php endforeach ?>
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('<?php echo $table_name ?>',true);
<?php if (isset($data)): ?>
        $this->_insert_data();
<?php endif ?>
    }

    public function down() {
        $this->dbforge->drop_table('<?php echo $table_name ?>',true);
    }
<?php if (isset($data)): ?>

    private function _insert_data(){
<?php foreach ($data as $row): ?>
<?php foreach ($field_name as $key => $value): ?>
        $table_data['<?php echo $value ?>'] = '<?php echo $row[$value] ?>';
<?php endforeach ?>
        $this->db->insert('<?php echo $table_name ?>',$table_data);
<?php endforeach ?>
    }
<?php endif ?>

}

/* Location: application/migrations/<?php echo $version ?>_Add_<?php echo $table_name ?>.php */