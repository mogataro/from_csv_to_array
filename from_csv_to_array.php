<?php
public function from_csv_to_array($csvFilePath){
          //読み出し時にCP932からUTF-8に変換するためのフィルター
          //%2Fは「/」をurlエンコードしたもの
          $filter = 'php://filter/read=convert.iconv.cp932%2Futf-8/resource='.$csvFilePath;

          $csvFile = new \SplFileObject($filter);
          $csvFile->setFlags(
                      \SplFileObject::READ_CSV          // CSV列として行を読み込み
                    | \SplFileObject::READ_AHEAD        // 先読み/巻き戻しで読み出し
                    | \SplFileObject::SKIP_EMPTY        // ファイルの空行を読み飛ばす
                    // | \SplFileObject::DROP_NEW_LINE  // 行末の改行を読みと飛ばす。カラムの改行をとばすこともあるのでコメントアウト
                );

                $csvFile->setCsvControl(",","\"");//区切り文字「,」囲み文字「"」

            } catch (\RuntimeException $e) {
                //csv読み込み時のエラー処理
            }

            $csvDataArray = [];
            if (empty($csvFile) == FALSE){
                // CSVファイルのデータを配列にセット

                foreach ($csvFile as $i => $csvLineData){
                    if ($i == 0) {
                        //先頭行（カラム名情報）を読み飛ばす
                        continue;
                    }

                    $j = 0;
                    $k = 0;
                    foreach( $csvLineData as $csvData ){
                        if($j >=7 && $j <= 9)
                        {
                            //必要なカラムのみ取り出す。ここでは8番目~10番目のカラムは配列に登録しない場合
                            $j++;
                            continue;
                        }
                        if($j >= 15)
                        {
                            //必要なカラムのみ取り出す。ここでは16番目以降のカラムは配列に登録しない場合
                            break;
                        }
      
                        $new_csvLineData[$k] = $csvData;
                        $j++;
                        $k++;
                    }
                    $csvDataArray[] = $new_csvLineData;
                }
            }
        
        return $csvDataArray;
}
