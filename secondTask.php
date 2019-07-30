<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");

use \Bitrix\Main\Loader;
 
if (!Loader::includeModule('iblock') || !Loader::includeModule('catalog'))
{
	die('Error loading module iblock or catalog');
}
 
$IBlockOffersCatalogId = 3; // ID инфоблока предложений (должен быть торговым каталогом)
$productName = "Товар"; // наименование товара
$offerName = "Торговое предложение"; // наименование торгового предложения
$offerPrice = 100.50; // Цена торгового предложения
 
 
$arCatalog = CCatalog::GetByID($IBlockOffersCatalogId);
 
$IBlockCatalogId = $arCatalog['PRODUCT_IBLOCK_ID']; // ID инфоблока товаров
$SKUPropertyId = $arCatalog['SKU_PROPERTY_ID']; // ID свойства в инфоблоке предложений типа "Привязка к товарам (SKU)"
 
$obElement = new CIBlockElement();
$arFields = array(
   'NAME' => $productName,
   'IBLOCK_ID' => $IBlockCatalogId,
   'ACTIVE' => 'Y'
);
$productId = $obElement->Add($arFields); // добавили товар, получили ID
 
if ($productId)
{
	$obElement = new CIBlockElement();
	// свойства торгвоого предложения
	$arOfferProps = array(
		$SKUPropertyId => $productId,
	);
	$arOfferFields = array(
		'NAME' => $offerName,
		'IBLOCK_ID' => $IBlockOffersCatalogId,
		'ACTIVE' => 'Y',
		'PROPERTY_VALUES' => $arOfferProps
	);
 
	$offerId = $obElement->Add($arOfferFields); // ID торгового предложения
 
	if ($offerId)
	{
		// добавляем как товар и указываем цену
		$catalogProductAddResult =	CCatalogProduct::Add(array(
				"ID" => $offersId,
				"VAT_INCLUDED" => "Y", //НДС входит в стоимость
			));
		if ($catalogProductAddResult && !CPrice::SetBasePrice($offerId, $offerPrice, "RUB"))
			throw new Exception("Ошибка установки цены торгового предложения \"{$offerId}\"");
		else
			throw new Exception("Ошибка добавления параметров торгового предложения \"{$offerId}\" в каталог товаров");
	}
	else
	{
		throw new Exception("Ошибка добавления торгового предложения: " . $obElement->LAST_ERROR);
	}
}
else
{
	throw new Exception("Ошибка добавления товара: " . $obElement->LAST_ERROR);
}
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>