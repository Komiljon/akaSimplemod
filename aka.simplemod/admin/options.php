<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;
IncludeModuleLangFile(__FILE__);

$moduleClass = "Csimplemod";
$moduleID = "aka.simplemod";
global  $APPLICATION;
$connection = Bitrix\Main\Application::getConnection();
$sqlHelper = $connection->getSqlHelper();
	
$RIGHT = $APPLICATION->GetGroupRight($moduleID);
if($RIGHT >= "R"){
	if(isset($_GET["id"]) && isset($_GET["status"])){
		$id = intVal($_GET["id"]);
		$stat = htmlspecialcharsbx($_GET["status"]);
		if($id>0 && ($stat=='N' || $stat=='Y' || $stat=='S')){
			$sql_up = "
				UPDATE aka_order_konsult_simplemod 
				SET STATUS = '".$stat."'
				WHERE ID = ".$id;
			$recordset_up = $connection->query($sql_up);
		}	
	}
unset($_GET["id"], $_GET["status"], $id, $stat);
?>

<div id="tbl_sale_order_result_div" class="adm-list-table-layout aka-list-table-layout">
	<div class="adm-list-table-wrap">		
		<table class="adm-list-table" id="aka_order_konsult">
			<tdead>
				<tr class="adm-list-table-header">
					<td class="adm-list-table-cell adm-list-table-cell-sort"><div class="adm-list-table-cell-inner">?? ????</div></td>
					<td class="adm-list-table-cell adm-list-table-cell-sort"><div class="adm-list-table-cell-inner">??? ?????</div></td>
					<td class="adm-list-table-cell adm-list-table-cell-sort"><div class="adm-list-table-cell-inner">????</div></td>
					<td class="adm-list-table-cell adm-list-table-cell-sort"><div class="adm-list-table-cell-inner">?????</div></td>
					<td class="adm-list-table-cell adm-list-table-cell-sort"><div class="adm-list-table-cell-inner">???</div></td>
					<td class="adm-list-table-cell adm-list-table-cell-sort"><div class="adm-list-table-cell-inner">????</div></td>
					<td class="adm-list-table-cell adm-list-table-cell-sort"><div class="adm-list-table-cell-inner">???</div></td>
				</tr>
			</tdead>
			<tbody>
				<?						
				    $sql = "SELECT * FROM aka_order_konsult_simplemod AS aoks ORDER BY aoks.`ID` ASC";
					$recordset = $connection->query($sql);
					while($record = $recordset->fetch()){
						$status = '<a title="???? ???" href="?id='. $record['ID'] . '&status=Y">? ????</a>';
						if($record['STATUS']=='Y'){
							$status = '<a title="???? ???" href="?id='. $record['ID'] . '&status=S">?????</a>';
						}
						if($record['STATUS']=='S'){
							$status = '<a title="???? ???" href="?id='. $record['ID'] . '&status=N">???</a>';
						}
				?>
						<tr class="adm-list-table-row">
							<td class="adm-list-table-cell"><?=$record['DATE_CREATE']?></td>
							<td class="adm-list-table-cell"><?=$record['ID']?></td>
							<td class="adm-list-table-cell"><?=$record['PHONE']?></td>
							<td class="adm-list-table-cell"><?=$record['USER']?></td>
							<td class="adm-list-table-cell"><?=$record['PRICE_SUM']?></td>
							<td class="adm-list-table-cell"><a target="_blank" href="/komplekt-velvex/<?=preg_replace('/[^0-9]/', '', $record['DETAIL_PAGE_URL']);?>/"><?=$record['PRODUCT_NAME']?></a></td>
							<td class="adm-list-table-cell"><?=$status?></td>
						</tr>
				<?
					}
				?>	
				<tr><td colspan="7" class="adm-list-table-cell adm-list-table-empty">-</td></tr>			
			</tbody>
		</table>				
	</div>
</div>
<?		
}
else{
	CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));
}
?>