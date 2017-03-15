<?php
/*
// Common class
*/


class CV extends FPDF
{
	/*
	// Deklaracje pol klasy
	*/
	
	
	const RealPath = '/var/www/cv/public_html/';		// full path to project
	const ImgPath = '/i/';							// images path
	
	private $_iX = 0;								// cursor position X
	private $_iY = 0;								// cursor position Y
	
	public $bDownload = FALSE;						// czy pobieramy plik
	
	
	/*
	// Constructor & destructor
	*/
	
	

	
	
	/*
	// Protected & private methods
	*/
	

	private function _roundedRect($x, $y, $w, $h, $r, $style = '')
	{
		$k = $this->k;
		$hp = $this->h;
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='B';
		else
			$op='S';
		$MyArc = 4/3 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
		$xc = $x+$w-$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

		$this->_arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
		$xc = $x+$w-$r ;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
		$this->_arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
		$xc = $x+$r ;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
		$this->_arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
		$xc = $x+$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
		$this->_arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out($op);
	}

	private function _arc($x1, $y1, $x2, $y2, $x3, $y3)
	{
		$h = $this->h;
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k, $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
	}
	
	function _circle($x, $y, $r, $style='D')
	{
		$this->_ellipse($x,$y,$r,$r,$style);
	}

	function _ellipse($x, $y, $rx, $ry, $style='D')
	{
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='B';
		else
			$op='S';
		$lx=4/3*(M_SQRT2-1)*$rx;
		$ly=4/3*(M_SQRT2-1)*$ry;
		$k=$this->k;
		$h=$this->h;
		$this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
			($x+$rx)*$k,($h-$y)*$k,
			($x+$rx)*$k,($h-($y-$ly))*$k,
			($x+$lx)*$k,($h-($y-$ry))*$k,
			$x*$k,($h-($y-$ry))*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			($x-$lx)*$k,($h-($y-$ry))*$k,
			($x-$rx)*$k,($h-($y-$ly))*$k,
			($x-$rx)*$k,($h-$y)*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			($x-$rx)*$k,($h-($y+$ly))*$k,
			($x-$lx)*$k,($h-($y+$ry))*$k,
			$x*$k,($h-($y+$ry))*$k));
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
			($x+$lx)*$k,($h-($y+$ry))*$k,
			($x+$rx)*$k,($h-($y+$ly))*$k,
			($x+$rx)*$k,($h-$y)*$k,
			$op));
	}
	
	private function _configure()
	{
		$this->Open();
		$this->SetDisplayMode('real');
		$this->SetAuthor("Jakub Łuczyński");
		$this->SetCreator("Jakub Łuczyński, powered by FPDF");
		$this->SetCompression(TRUE);
		$this->SetAutoPageBreak(TRUE, 10);
		$this->SetSubject('Jakub Luczynski - Curriculum vitae');
		$this->SetTitle('Jakub Luczynski - Curriculum vitae');

		//$this->AddFont('courier', 'I');
		//$this->AddFont('arial', '', 'arial.ttf', true);
		//$this->AddFont('arial', 'B', 'arialbd.ttf', true);
		
		//$this->AddFont('times', '', 'times.ttf', true);
		//$this->AddFont('times', 'I', 'timesi.ttf', true);
		//$this->AddFont('times', 'B', 'timesbd.ttf', true);
		
		$this->AddFont('verdana', '', 'verdana.ttf', true);
		$this->AddFont('verdana', 'B', 'verdanab.ttf', true);
		$this->AddFont('verdana', 'BI', 'verdanaz.ttf', true);
		$this->AddFont('verdana', 'I', 'verdanai.ttf', true);
		
		//$this->AddFont('georgia', '', 'georgia.ttf', true);
		//$this->AddFont('georgia', 'B', 'georgiab.ttf', true);
		//$this->AddFont('georgia', 'BI', 'georgiaz.ttf', true);
		//$this->AddFont('georgia', 'I', 'georgiai.ttf', true);
		
		$this->AddFont('tahoma', '', 'tahoma.ttf', true);
		$this->AddFont('tahoma', 'B', 'tahomabd.ttf', true);
		
		$this->AddFont('dejavu', '', 'DejaVuSansCondensed.ttf', true);
		$this->AddFont('dejavu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
		$this->AddFont('dejavu', 'BI', 'DejaVuSansCondensed-BoldOblique.ttf', true);
		$this->AddFont('dejavu', 'I', 'DejaVuSansCondensed-Oblique.ttf', true);
		
		//$this->AddFont('dejavus', '', 'DejaVuSerif.ttf', true);
		//$this->AddFont('dejavus', 'B', 'DejaVuSerif-Bold.ttf', true);
		//$this->AddFont('dejavus', 'BI', 'DejaVuSerif-BoldItalic.ttf', true);
	}
	
	private function _page()
	{
		$this->SetMargins(1, 1, 1);
		
		$this->AddPage();
		
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('dejavu', '', 8);
		$this->SetXY(0, 0);
	}
	
	private function _header()
	{
		$this->SetXY(0, 0);
		
		//$this->SetFont('arial', 'B', 8); $this->Text(0, 10, 'ßüöäÜÖÄ óąśłżźćńÓĄŁŚŻŹĆŃ');
		
		$this->SetLineWidth(0.1);
		$this->SetDrawColor(200, 200, 200);
		$this->SetFillColor(245,246,244);
		$this->Rect(0, 0, 210, 45, 'F');
		
		$this->SetTextColor(76, 76, 76);
		$this->SetFont('tahoma', 'B', 30); $this->Text(85, 17, 'JAKUB');
		$this->SetFont('tahoma', 'B', 30); $this->Text(72, 28, 'ŁUCZYŃSKI');
		
		$this->SetTextColor(138, 138, 138);
		$this->SetFont('tahoma', '', 8); $this->Text(64, 33, 'WEB DEVELOPER, PHP PROGRAMMER & PROJECT MANAGER');
		
		$this->SetTextColor(180, 180, 180); $this->SetXY(115.5, 32);
		$this->SetFont('tahoma', 'B', 5); $this->Write(6, 'CV programmed with FPDF', 'http://www.fpdf.org/');
		
		$this->Image('i/photo.png', 15, 5, 30, 37, 'PNG', 'mailto:jakub.luczynski@gmail.com');
		$this->Rect(15, 5, 30, 37);
		
		$this->Rect(9, 6, 5, 3); $this->Image('i/en.png', 9, 6, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?en');
		$this->Rect(9, 10, 5, 3); $this->Image('i/de.png', 9, 10, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?en');
		$this->Rect(9, 14, 5, 3); $this->Image('i/pl.png', 9, 14, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?pl');
		if (!$this->bDownload) { $this->Image('i/save.png', 10, 18, 3, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?download&pl'); }
		
		/*
		$this->Rect(9, 6, 5, 3); $this->Image('i/en.png', 9, 6, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?en');
		$this->Rect(9, 10, 5, 3); $this->Image('i/pl.png', 9, 10, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?pl');
		if (!$this->bDownload) { $this->Image('i/save.png', 10, 18, 3, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?download&pl'); }
		*/

		$this->Line(0, 39, 15, 39);
		$this->Line(45, 39, 210, 39);
		$this->Line(0, 45, 210, 45);
		
		$this->SetFillColor(235,235,235);
		$this->SetFont('verdana', '', 6);
		$this->SetTextColor(50, 50, 50);
		
		$this->Image('i/phone.png', ($this->_iX = 47), 40.3, 3, 3, 'PNG');
		$this->SetXY(($this->_iX + 3), 39);
		$this->Cell(10, 6, '+48 501 306 033 (17:00 - 18:30)', 0, 0, 'L', FALSE); //+48 505 521 094
		
		$this->Image('i/email.png', ($this->_iX = 91), 40, 4, 4, 'PNG');
		$this->SetXY(($this->_iX + 4), 39);
		$this->Cell(10, 6, 'jakub.luczynski@gmail.com', 0, 0, 'L', FALSE, 'mailto:jakub.luczynski@gmail.com');
		
		$this->Image('i/goldenline.png', ($this->_iX = 128), 40, 4, 4, 'PNG');
		$this->SetXY(($this->_iX + 4), 39);
		$this->Cell(10, 6, '/jakub-luczynski', 0, 0, 'L', FALSE, 'http://www.goldenline.pl/jakub-luczynski/');
		
		//$this->Image('i/facebook.png', ($this->_iX = 166), 40, 4, 4, 'PNG');
		//$this->SetXY(($this->_iX + 5), 39);
		//$this->Cell(10, 6, '/luczynski.jakub', 0, 0, 'L', FALSE, 'https://www.facebook.com/luczynski.jakub');
		
		$this->Image('i/linkedin.png', ($this->_iX = 153), 40, 4, 4, 'PNG');
		$this->SetXY(($this->_iX + 4), 39);
		$this->Cell(10, 6, '/jakubluczynski', 0, 0, 'L', FALSE, 'http://pl.linkedin.com/in/jakubluczynski');
		
		$this->Image('i/skype.png', ($this->_iX = 177), 39.5, 5, 5, 'PNG');
		$this->SetXY(($this->_iX + 4), 39);
		$this->Cell(10, 6, 'luczynski.jakub', 0, 0, 'L', FALSE);
		
		$this->SetFont('dejavu', '', 8);
		
		$this->_roundedRect(153, 5, 22, 4, 1, 'FD');
		$this->SetXY(153, 4.2); $this->Cell(10, 6, 'Data urodzenia', 0, 0, 'L', FALSE);
		$this->SetXY(175, 4.2); $this->Cell(10, 6, '19/01/1979', 0, 0, 'L', FALSE);
		
		$this->_roundedRect(153, 10, 22, 4, 1, 'FD');
		$this->SetXY(153, 9.2); $this->Cell(10, 6, 'Narodowość', 0, 0, 'L', FALSE);
		$this->SetXY(175, 9.2); $this->Cell(10, 6, 'Polak', 0, 0, 'L', FALSE);
		
		$this->_roundedRect(153, 15, 22, 4, 1, 'FD');
		$this->SetXY(153, 14.2); $this->Cell(10, 6, 'Lokalizacja', 0, 0, 'L', FALSE);
		$this->SetXY(175, 14.2); $this->Cell(10, 6, 'Polska, Łódź', 0, 0, 'L', FALSE);
		
		$this->_roundedRect(153, 20, 22, 4, 1, 'FD');
		$this->SetXY(153, 19.2); $this->Cell(10, 6, 'Adres', 0, 0, 'L', FALSE);
		$this->SetXY(175, 19.6); $this->MultiCell(30, 5, "Zakładowa 45 / 20\r\n92-402, łódzkie, Łódź", 0, 'L', FALSE);
		
		$this->_roundedRect(153, 30, 22, 4, 1, 'FD');
		$this->SetXY(153, 29.2); $this->Cell(10, 6, 'Doświadczenie', 0, 0, 'L', FALSE);
		$this->SetXY(175, 29.2); $this->Cell(10, 6, '14 lat', 0, 0, 'L', FALSE);
	}
	
	private function _experience()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(245,246,244);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(5, 56, 147, 56);
		$this->SetLineWidth(0.2); $this->Line(7, 58, 7, 275);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(8, 50);
		$this->Cell(100, 6, 'Doświadczenie zawodowe', 0, 0, 'L', FALSE);
		
		
		$this->SetXY(8, 59); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2004 (zlecenia), 2005 - 2008 (1/2 etatu), 2009 - 08.2015 (pełny etat)', 0, 0, 'L', FALSE);
		$this->SetXY(8, 64); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Kierownik IT / projektant systemu / programista PHP / informatyk', 0, 0, 'L', FALSE);
		$this->SetXY(10, 70); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'GANDALF SP. Z O.O. (Empik Media & Fashion Group) / Księgarnia Internetowa "Gandalf"', 0, 0, 'L', FALSE);
		$this->SetXY(10, 76); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Stworzenie i rozwój autorskiego, dedykowanego systemu e-commerce opartego o mój własny framework MVC. Sprawowanie kontroli nad systemem, planowanie zadań i zarządzanie czasem pracowników (SVN / Trac), planowanie budżetu, tworzenie raportów, projektowanie i tworzenie dedykowanych aplikacji, analizy, testy, nadzór nad pracami i poprawnością działania aplikacji, optymalizacja i refaktoryzacja kodów, implementacja nowych rozwiązań (APC, Percona Cluster, Solr), wykonywanie obowiązków ABI", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Kontakt: Anetta Wilczyńska, (+48 42) 252 39 23, (+48 42) 292 00 99, http://www.gandalf.com.pl, Łódź, Polska', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(29, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Referencje', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/references_gandalf.zip'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/references_gandalf.zip');
		$this->SetXY(16, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Przykłady', '', 0, 'C', FALSE, 'http://cv.creolink.pl/examples/gandalf.zip'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/examples/gandalf.zip');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2013 - 08.2015 (zlecenia)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Projektant systemu / programista', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'TPNETS.COM - TOMASZ BATHELT, PIOTR MARCINIAK', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Zaprojektowanie i wykonanie autorskiego, dedykowanego systemu B2C do zarządzania firmą, sieciami i klientami oraz elektronicznego biura obsługi klienta. Długoterminowa współpraca przy rozwoju aplikacji.\r\nDo CV załączono referencje. Demo dostępne pod adresem: https://tps.demo.mnc.pl/", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Kontakt: Tomasz Bathelt, Piotr Marciniak, (+48 42) 636-98-96, http://tpnets.com/, Łódź, Polska', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(43, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Referencje', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/references_tpnets.zip'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/tpnets.jpg');
		$this->SetXY(30, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Przykłady', '', 0, 'C', FALSE, 'http://cv.creolink.pl/examples/tpnets.zip'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/examples/tpnets.zip');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2013 - 08.2015 (zlecenia)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Projektant sklepu WWW, systemu CMS i CRM / programista', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'FREEPERS S.C', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Wykonanie autorskiej aplikacji dedykowanego sklepu internetowego wraz z systemem zarządzania jego konfiguracją, klientami, zamówieniami oraz katalogiem produktów (w trakcie prac, planowane zakończenie kwiecień - maj 2015). Wykonanie aplikacji do księgowania składanych zamówień w programie Subiekt. Współpraca przy rozwoju programów, doradztwo.", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Kontakt: Krzysztof Rogalski, (+48 42) 239 41 77, (+48 42) 791 24 95 30, http://fripers.pl, Łódź, Polska', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(36, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Referencje', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/freepers_pl.jpg'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/freepers_pl.jpg');
		$this->SetXY(23, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Przykłady', '', 0, 'C', FALSE, 'http://cv.creolink.pl/examples/freepers.zip'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/examples/freepers.zip');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2002 - 2009 (pełny etat), 2012 - 05.2015 (zlecenia)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Kierownik projektu / główny programista / informatyk', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'FORWEB S.C.', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Zaprojektowanie, wykonanie i wdrożenie autorskiego systemu B2B/B2C do obsługi firm ISP. Koordynacja zadań w dziale IT. Analiza, spotkania z klientami, projektowanie oraz oprogramowywanie stron WWW (sklepy internetowe, prezentacje WWW, wizytówki i portale firmowe). Nadzór i obsługa techniczna istniejących programów. Do CV załączono referencje. Demo dostępne pod adresem http://mms.4web.pl/, http://demo.4web.pl/.", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Kontakt: Tomasz Pawłowski, (+48 42) 235 1000, http://www.forweb.pl, Łódź, Polska', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(53, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Referencje', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/references_forweb.zip'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/forweb.jpg');
		$this->SetXY(40, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Przykłady', '', 0, 'C', FALSE, 'http://cv.creolink.pl/examples/forweb.zip'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/examples/forweb.zip');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2003, 2005, 2007 - 2009 (zlecenia)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Projektant portali / programista', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'STAWOZ SP Z O.O', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Wykonanie i wdrożenie autorskiego systemu portali internetowych http://www.portal-ppoz.pl/, http://www.portal-bhp.pl/, http://www.portal-ekologia.pl/ wraz z CMS. Aktywne pozycjonowanie, obsługa techniczna, rozwijanie oraz nadzór nad portalami. Portale zostały zamknięte. Do CV załączono referencje.", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Kontakt: Stanisław Woźnica, (+48 42) 673 57 05, (+48 42) 602 290 306, http://www.stawoz.pl, Łódź, Polska', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(31, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Referencje', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/references_stawoz.zip'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/stawoz.jpg');
		//$this->SetXY(30, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Przykłady', '', 0, 'C', FALSE, 'http://www.goldenline.pl/jakub-luczynski/'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?download&en');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2004 - 2006 (zlecenia)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Freelancer / programista', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'IIZT.COM', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Wykonywanie projektów WWW dla holenderskiej grupy IIZT (http://www.iizt.com). Praca zdalna. Do CV załączono referencje.", 0, 'L', FALSE);
		$this->_iY = ($this->GetY()); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Kontakt: Todd Wilkinson, todd@sacramentomarketinglabs.com, http://iizt.com, Amsterdam, Holandia', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(39, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Referencje', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/iizt.jpg'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/iizt.jpg');
		//$this->SetXY(30, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Przykłady', '', 0, 'C', FALSE, 'http://www.goldenline.pl/jakub-luczynski/'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?download&en');

		
		$this->SetTextColor(90, 90, 90);
	}
	
	private function _agreement()
	{
		$this->SetXY(0, 281);
		$this->SetFont('verdana', '', 6);
		
		//$this->MultiCell(210, 3, "Wyrażam zgodę Trust Consulting HR na umieszczanie moich danych osobowych w bazie danych firmy i przetwarzanie ich w celach niezbędnych do realizacji procesu rekrutacji,\r\nzgodnie z Ustawą z dnia 29.08.1997 r. o Ochronie danych Osobowych (Dz. U.Nr poz 833).", 0, 'C', FALSE); // dla Trust Consulting HR
		
		$this->MultiCell(210, 3, "Wyrażam zgodę na przetwarzanie moich danych osobowych na potrzeby niezbędne do procesu realizacji rekrutacji\r\n(zgodnie z ustawą z dnia 29.08.1997 roku o Ochronie Danych Osobowych; tekst jednolity: Dz. U. z 2002r. Nr 101, poz. 926 ze zm.)", 0, 'C', FALSE);
	}
	
	private function _goals()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(245,246,244);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 56, 204, 56);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 50);
		$this->Cell(100, 6, 'Cele zawodowe', 0, 0, 'L', FALSE);
		
		$this->SetFont('tahoma', '', 8);
		$this->SetXY(151, 59);
		$this->MultiCell(54, 4, "Z pasji programista PHP, z doświadczenia kierownik projektu lub zespołu.\r\n\r\nNajlepiej czuję się jako programista przy dużych i ciekawych projektach internetowych. Lubię wyzwania i nowe rozwiązania techniczne. Jestem zorganizowany i bardzo dokładny. Chętnie pracuję w zespole. Ceniony jestem za pracę samodzielną (zdalną).", 0, 'L', FALSE);
	}
	
	private function _skills()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(90, 90, 90);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 107, 204, 107);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 100);
		$this->Cell(100, 8, 'Technicznie', 0, 0, 'L', FALSE);
		
		$this->SetLineWidth(0.2);
		$this->SetFont('tahoma', '', 8);
		
		$this->_skill(($iY = 108), 'PHP5 (OOP), MVC', 6);
		$this->_skill(($iY+=4), 'MySQL, Percona', 6);
		$this->_skill(($iY+=4), 'HTML5, XHTML, CSS', 6);
		$this->_skill(($iY+=4), 'JS, jQuery, AJAX', 5);
		$this->_skill(($iY+=4), 'PHP FPM, APC', 5);
		$this->_skill(($iY+=4), 'SOAP, REST, XML', 5);
		$this->_skill(($iY+=4), 'SVN, GIT, TRAC', 5);
		$this->_skill(($iY+=4), 'LINUX, Debian', 4);
		$this->_skill(($iY+=4), 'NGINX, LT, APACHE', 4);
		$this->_skill(($iY+=4), 'SOLR, SPHINX', 4);
		$this->_skill(($iY+=4), 'GA, ADWORDS, SEO', 4);
		$this->_skill(($iY+=4), 'WEB, UI DESIGN', 4);
		$this->_skill(($iY+=4), 'SYMFONY 2 / ZF 2', 3);
		$this->_skill(($iY+=4), 'MSSQL, PostgreSQL', 2);
		$this->_skill(($iY+=4), 'PERL, PYTHON', 2);
		$this->_skill(($iY+=4), 'C#, JAVA, C++', 1);
	}
	
	private function _skill($p_iY = 107, $p_sTxt = '', $p_iVal = 5)
	{
		$iY = ((int)($p_iY));
		$sTxt = trim((string)($p_sTxt));
		$iVal = ((int)($p_iVal));
		
		$this->SetXY(151, $iY);
		$this->Cell(53, 6, $sTxt, 0, 0, 'L', FALSE);
		
		$this->_iY = $iY + 3; $this->_iX = 182;
		$this->_circle($this->_iX, $this->_iY, 1.5, 'D'); $this->_circle($this->_iX, $this->_iY, 1, 'FD');
		$this->_circle(($this->_iX + 4), $this->_iY, 1.5, 'D'); if ($iVal >= 2) { $this->_circle(($this->_iX + 4), $this->_iY, 1, 'FD'); }
		$this->_circle(($this->_iX + 8), $this->_iY, 1.5, 'D'); if ($iVal >= 3) { $this->_circle(($this->_iX + 8), $this->_iY, 1, 'FD'); }
		$this->_circle(($this->_iX + 12), $this->_iY, 1.5, 'D'); if ($iVal >= 4) { $this->_circle(($this->_iX + 12), $this->_iY, 1, 'FD'); }
		$this->_circle(($this->_iX + 16), $this->_iY, 1.5, 'D'); if ($iVal >= 5) { $this->_circle(($this->_iX + 16), $this->_iY, 1, 'FD'); }
		$this->_circle(($this->_iX + 20), $this->_iY, 1.5, 'D'); if ($iVal >= 6) { $this->_circle(($this->_iX + 20), $this->_iY, 1, 'FD'); }
	}
	
	private function _personal()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(90, 90, 90);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 180, 204, 180);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 174);
		$this->Cell(100, 6, 'Personalnie', 0, 0, 'L', FALSE);
		
		$this->SetLineWidth(0.2);
		$this->SetFont('tahoma', '', 8);
		
		$this->_skill(($iY = 181), 'Organizacja', 6);
		$this->_skill(($iY+=4), 'Solidność', 6);
		$this->_skill(($iY+=4), 'Pracowitość', 5);
		$this->_skill(($iY+=4), 'Staranność', 5);
		$this->_skill(($iY+=4), 'Terminowość', 5);
		//$this->_skill(($iY+=4), 'Komunikatywność', 4);
		//$this->_skill(($iY+=4), 'Kreatywność', 4);
		//$this->_skill(($iY+=4), 'Towarzyskość', 4);
	}
	
	private function _languages()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(90, 90, 90);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 210, 204, 210);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 204);
		$this->Cell(100, 6, 'Języki', 0, 0, 'L', FALSE);
		
		$this->SetLineWidth(0.2);
		$this->SetFont('tahoma', '', 8);
		
		$this->_skill(($iY = 210), 'Polski', 6);
		$this->_skill(($iY+=4), 'Angielski (C1)', 5);
		$this->_skill(($iY+=4), 'Niemiecki (A2)', 2);
	}
	
	private function _education()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(90, 90, 90);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 231, 204, 231);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 225);
		$this->Cell(100, 6, 'Edukacja i kursy', 0, 0, 'L', FALSE);
		
		$this->SetFont('tahoma', '', 8);
		$this->SetXY(151, 233);
		$this->MultiCell(53, 4, "szkolenie na ABI, profesjonalne szkolenia GA, prawo jazdy kat. B, rozpoczęte studia na Politechnice Łódzkiej (3 lata) - informatyka", 0, 'L', FALSE);
	}
	
	private function _hobby()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(90, 90, 90);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 260, 204, 260);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 254);
		$this->Cell(100, 6, 'Hobby i sport', 0, 0, 'L', FALSE);
		
		$this->SetFont('tahoma', '', 8);
		$this->SetXY(151, 262);
		$this->MultiCell(53, 4, "gry komputerowe, filmy i seriale, komputery, programowanie, giełda, piłka nożna, jazda konna, tenis stołowy", 0, 'L', FALSE);
	}
	
	private function _references($p_sFile = '')
	{
		$sFile = trim((string)($p_sFile));
		
		if ($aFData = Lib::checkFile('i/', $sFile))
		{
			$this->_page();
			$this->Image($aFData[2], 5, 5, 200, 280, $aFData[1]);
		}
	}
	
	
	/*
	// Public methods
	*/
	
	
	public function render()
	{
		$this->_configure();
		$this->_page();
		
		$this->_header(); // header
		$this->_experience(); // experience
		$this->_goals(); // goals
		$this->_skills(); // skills
		$this->_personal(); // personal
		$this->_languages(); // languages
		$this->_education(); // education
		$this->_hobby(); // hobby
		$this->_agreement(); // agreement
		
		$this->_references('gandalf_referencje_pl_1'); // references
		$this->_references('gandalf_referencje_pl_2'); // references
		$this->_references('tpnets'); // references
		$this->_references('forweb'); // references
		$this->_references('freepers_pl'); // references
		$this->_references('stawoz'); // references
		$this->_references('iizt'); // references
	}
}
?>
