<section>
    <div class="row data_opn">
        <table align="center" border="1" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td bgcolor="#ffffff"style="padding: 20px 0 20px 19px;">
                    <label><?php echo $this->lang->line('SUBJECT'); ?>  :</label>
                    <div class="clearfix"></div>
                    <?php echo $email[0]['Subject'] ?>
                    <div class="clearfix"></div>
                    <label><?php echo 'Message' ?>:</label>
                    <div class="clearfix"></div>
                    <?php echo $email[0]['Body'] ?> 
                </td>
            </tr>
           
        </table>
 </div>
</section>