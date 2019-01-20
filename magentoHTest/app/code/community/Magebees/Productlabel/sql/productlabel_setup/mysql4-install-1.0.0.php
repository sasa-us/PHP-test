<?php

$installer = $this;

$installer->startSetup();

$installer->run(
    "

CREATE TABLE IF NOT EXISTS {$this->getTable('cws_productlabel')} (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL,
  `hide` tinyint(1) NOT NULL,
  `sort_order` smallint(6) NOT NULL,
  `title` varchar(255) NOT NULL,
  `stores` varchar(255) NOT NULL,
  `customer_group_ids` varchar(255) NOT NULL,
  `prod_text` varchar(255) NOT NULL,
  `prod_image` varchar(255) NOT NULL,
  `prod_position` varchar(2) NOT NULL,
  `prod_text_color` varchar(255) NOT NULL,
  `prod_text_size` int(11) NOT NULL,
  `cat_text` varchar(255) NOT NULL,
  `cat_image` varchar(255) NOT NULL,
  `cat_position` varchar(2) NOT NULL,
  `cat_text_color` varchar(255) NOT NULL,
  `cat_text_size` int(11) NOT NULL,
  `include_sku` tinyint(1) NOT NULL,
  `include_cat` tinyint(1) NOT NULL,
  `attr_code` varchar(255) NOT NULL,
  `attr_value` varchar(255) NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `is_sale` tinyint(1) NOT NULL,
  `date_enabled` tinyint(1) NOT NULL,
  `from_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL,
  `price_enabled` tinyint(1) NOT NULL,
  `from_price` decimal(12,4) NOT NULL,
  `to_price` decimal(12,4) NOT NULL,
  `by_price` tinyint(4) NOT NULL,
  `stock_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`label_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	
CREATE TABLE IF NOT EXISTS {$this->getTable('cws_productlabel_category')} (
  `label_category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label_id` int(11) NOT NULL,
  `category_ids` int(11) NOT NULL,
  PRIMARY KEY (`label_category_id`),
  KEY `IDX_PRODUCTLABEL_CATEGORY_LABEL_ID` (`label_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Product labels To Category ids Relations' AUTO_INCREMENT=1 ;
	
CREATE TABLE IF NOT EXISTS {$this->getTable('cws_productlabel_product')} (
  `label_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `label_id` int(11) NOT NULL,
  `product_sku` varchar(255) NOT NULL,
  PRIMARY KEY (`label_product_id`),
  KEY `IDX_PRODUCTLABEL_PRODUCT_LABEL_ID` (`label_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Product labels To Product SKUs Relations' AUTO_INCREMENT=1 ;

ALTER TABLE {$this->getTable('cws_productlabel_category')}
  ADD CONSTRAINT `cws_productlabel_category_ibfk_1` FOREIGN KEY (`label_id`) REFERENCES {$this->getTable('cws_productlabel')} (`label_id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
ALTER TABLE {$this->getTable('cws_productlabel_product')}
  ADD CONSTRAINT `cws_productlabel_product_ibfk_1` FOREIGN KEY (`label_id`) REFERENCES {$this->getTable('cws_productlabel')} (`label_id`) ON DELETE CASCADE ON UPDATE CASCADE;
"
);

$installer->endSetup(); 
