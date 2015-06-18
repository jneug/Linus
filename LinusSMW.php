<?php

use SMW\MediaWiki\ByLanguageCollationMapper;

class ResponsiveCategory extends SMWCategoryResultPrinter {

  public function getName() {
    return wfMessage( 'linussmw_responsive_category' )->text();
  }

  protected function getResultText( SMWQueryResult $res, $outputMode ) {
    // Print all result rows:
		$rowindex = 0;
		$row = $res->getNext();

    // Arrays to pass to LinusCategoryViewer
    $articles = array();
    $articles_start_char = array();

    while ( $row !== false ) {
      $nextrow = $res->getNext(); // look ahead

      if ( !isset( $row[0] ) ) {
				$row = $nextrow;
				continue;
			}

      $content = $row[0]->getContent();

      if ( !isset( $content[0] ) || !( $content[0] instanceof SMWDataItem ) ) {
				$row = $nextrow;
				continue;
			}

      $articles_start_char[] = $this->getFirstLetter( $res, $content[0] );

      foreach ( $row as $field ) {
        $fieldValues = array();

        // $columnIndex = $this->getFirstLetter( $res, $field->getResultSubject() );

        while ( ( $text = $field->getNextText( SMW_OUTPUT_WIKI, $this->getLinker(true) ) ) !== false ) {
          $fieldValues[] = $text;
        }

        // Always sort the column value list in the same order
        natsort( $fieldValues );
        $articles[] = implode( ( $this->mDelim ? $this->mDelim : ',' ) . ' ', $fieldValues ) . ' ';
      }

			$row = $nextrow;
			$rowindex++;
    }

    // Make label for finding further results
		if ( $this->linkFurtherResults( $res ) ) {
      $articles[] = $this->getLink( $res, $outputMode )->getText( SMW_OUTPUT_WIKI, $this->mLinker );
      $articles_start_char[] = '#';
		}

    $list = call_user_func_array(array('LinusCategoryViewer', LinusCategoryViewer::$mCategoryLayout), array($articles, $articles_start_char));
    return $list;
  }

  private function getFirstLetter( SMWQueryResult $res, SMWDataItem $dataItem ) {
    $sortKey = $dataItem->getSortKey();

    if ( $dataItem->getDIType() == SMWDataItem::TYPE_WIKIPAGE ) {
      $sortKey = $res->getStore()->getWikiPageSortKey( $dataItem );
    }

    return strtoupper(substr($sortKey,0,1));
    // return ByLanguageCollationMapper::getInstance()->findFirstLetterForCategory( $sortKey );
  }
}

// class LinusSMW_Buttons extends SMWCategoryResultPrinter {
//
//
//
// }
