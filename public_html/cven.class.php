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
		$this->SetAuthor("Jakub Luczynski");
		$this->SetCreator("Jakub Luczynski, powered by FPDF");
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
		$this->SetFont('tahoma', 'B', 30); $this->Text(72, 28, 'LUCZYNSKI');
		
		$this->SetTextColor(138, 138, 138);
		$this->SetFont('tahoma', '', 8); $this->Text(64, 33, 'WEB DEVELOPER, PHP PROGRAMMER & PROJECT MANAGER');
		
		$this->SetTextColor(180, 180, 180); $this->SetXY(115.5, 32);
		$this->SetFont('tahoma', 'B', 5); $this->Write(6, 'CV programmed with FPDF', 'http://www.fpdf.org/');
		
		$this->Image('i/photo.png', 15, 5, 30, 37, 'PNG', 'mailto:jakub.luczynski@gmail.com');
		$this->Rect(15, 5, 30, 37);
		
		///*
		$this->Rect(9, 6, 5, 3); $this->Image('i/en.png', 9, 6, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?en');
		$this->Rect(9, 10, 5, 3); $this->Image('i/de.png', 9, 10, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?en');
		$this->Rect(9, 14, 5, 3); $this->Image('i/pl.png', 9, 14, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?pl');
		if (!$this->bDownload) { $this->Image('i/save.png', 10, 18, 3, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?download&en'); }
		//*/
		
/*
		$this->Rect(9, 6, 5, 3); $this->Image('i/en.png', 9, 6, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?en');
		$this->Rect(9, 10, 5, 3); $this->Image('i/pl.png', 9, 10, 5, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?pl');
		if (!$this->bDownload) { $this->Image('i/save.png', 10, 18, 3, 3, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?download&en'); }
*/		
		$this->Line(0, 39, 15, 39);
		$this->Line(45, 39, 210, 39);
		$this->Line(0, 45, 210, 45);
		
		$this->SetFillColor(235,235,235);
		$this->SetFont('verdana', '', 6);
		$this->SetTextColor(50, 50, 50);
		
		$this->Image('i/phone.png', ($this->_iX = 59), 40.3, 3, 3, 'PNG');
		$this->SetXY(($this->_iX + 3), 39);
		$this->Cell(10, 6, '+49 1521 7786892', 0, 0, 'L', FALSE); // +48 505 521 094
		
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
		$this->SetXY(153, 4.2); $this->Cell(10, 6, 'Date of birth', 0, 0, 'L', FALSE);
		$this->SetXY(175, 4.2); $this->Cell(10, 6, '19/01/1979', 0, 0, 'L', FALSE);
		
		$this->_roundedRect(153, 10, 22, 4, 1, 'FD');
		$this->SetXY(153, 9.2); $this->Cell(10, 6, 'Nationality', 0, 0, 'L', FALSE);
		$this->SetXY(175, 9.2); $this->Cell(10, 6, 'Polish', 0, 0, 'L', FALSE);
		
		$this->_roundedRect(153, 15, 22, 4, 1, 'FD');
		$this->SetXY(153, 14.2); $this->Cell(10, 6, 'Location', 0, 0, 'L', FALSE);
		$this->SetXY(175, 14.2); $this->Cell(10, 6, 'Germany', 0, 0, 'L', FALSE);
		
		$this->_roundedRect(153, 20, 22, 4, 1, 'FD');
		$this->SetXY(153, 19.2); $this->Cell(10, 6, 'Address', 0, 0, 'L', FALSE);
		$this->SetXY(175, 19.6); $this->MultiCell(35, 5, "Am Wall 54\r\n14532, Kleinmachnow", 0, 'L', FALSE);
		
		$this->_roundedRect(153, 30, 22, 4, 1, 'FD');
		$this->SetXY(153, 29.2); $this->Cell(10, 6, 'Experience', 0, 0, 'L', FALSE);
		$this->SetXY(175, 29.2); $this->Cell(10, 6, '14 years', 0, 0, 'L', FALSE);
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
		$this->Cell(100, 6, 'Employment history', 0, 0, 'L', FALSE);
		
		
		$this->SetXY(8, 59); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2004 (commissions), 2005 - 2008 (part time), 2009 - 08.2015 (full time)', 0, 0, 'L', FALSE);
		$this->SetXY(8, 64); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'IT Manager / IT Specialist', 0, 0, 'L', FALSE);
		$this->SetXY(10, 70); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'GANDALF SP. Z O.O. (Empik Media & Fashion Group) / Online Bookstore "Gandalf"', 0, 0, 'L', FALSE);
		$this->SetXY(10, 76); $this->SetFont('tahoma', '', 8);
		//$this->MultiCell(138, 4, "Design and development of dedicated, proprietary e-commerce software. Projects management, scheduling and supervision of work, taking responsibility over employees, budgeting, reporting, design and development of dedicated applications, analysis creation, software tests, implementation of changes, supervision over the online store and intranet system", 0, 'L', FALSE);
		$this->MultiCell(138, 4, "Design of dedicated, proprietary e-commerce system based on my own MVC framework. Coding, optimization and refactoring, analysis, coordination and organization of work at IT Section (SVN, Trac). System development by implementation of new technical solutions (PHP5-FPM, APC, Percona Cluster, Nginx, Solr, load balancing, VMware ESX). Integration with external systems using API (SOAP, REST, XML, CSV).", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Contact: Anetta Wilczynska, (+48 42) 252 39 23, (+48 42) 292 00 99, http://www.gandalf.com.pl, Lodz, Poland', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(29, $this->_iY - 0.1); $this->Cell(10, 2.2, 'References', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/references_gandalf.zip'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/references_gandalf.zip');
		$this->SetXY(16, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Examples', '', 0, 'C', FALSE, 'http://cv.creolink.pl/examples/gandalf.zip'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/examples/gandalf.zip');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2013 - 08.2015 (commissions)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'System designer / PHP programmer', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'TPNETS.COM - TOMASZ BATHELT, PIOTR MARCINIAK', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Design and development of dedicated, proprietary B2C system for ISP (network management, customer relationship management, electronic customer office). Coding with MySQL, PHP5 OOP, JavaScript (jQuery, Ajax), CSS, HTML5 using my own, very fast and flexible, MVC framework and template system. Online, remote work. Demo at: https://tps.demo.mnc.pl/", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); 
		$this->Cell(138, 2, 'Contact: Tomasz Bathelt, Piotr Marciniak, (+48 42) 636-98-96, http://tpnets.com/, Lodz, Poland', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(43, $this->_iY - 0.1); $this->Cell(10, 2.2, 'References', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/references_tpnets.zip'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/references_tpnets.zip');
		$this->SetXY(30, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Examples', '', 0, 'C', FALSE, 'http://cv.creolink.pl/examples/tpnets.zip'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/examples/tpnets.zip');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2013 - 08.2015 (commissions)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'e-Commerce software designer / PHP programmer', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'FREEPERS', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Design and development of dedicated, proprietary application of e-commerce system with CMS and CRM modules. Comprehensive implementation of solution with server based on Linux, Nginx, Percona Server and my own extremely fast and flexible framework based on MVC, PHP5 OOP, MySQL, HTML5, CSS, JS (jQuery), Python. Integration with external systems using SOAP / REST. Advising, coaching. Project documentation.", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Contact: Krzysztof Rogalski, (+48 42) 239 41 77, (+48 42) 791 24 95 30, http://fripers.pl, Lodz, Poland', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(36, $this->_iY - 0.1); $this->Cell(10, 2.2, 'References', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/freepers_en.jpg'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/freepers_en.jpg');
		$this->SetXY(23, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Examples', '', 0, 'C', FALSE, 'http://cv.creolink.pl/examples/freepers.zip'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/examples/freepers.zip');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2002 - 2009 (full time), 2012 - 05.2015 (commissions)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Project manager / Lead PHP programmer / IT specialist', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'FORWEB S.C.', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Design, implementation and development of dedicated, proprietary B2B and B2C system for ISP (demo at http://mms.4web.pl/, http://demo.4web.pl/). Coordination of tasks at IT Section. Meetings with customers, project analysis, optimization and refactoring, coding with SQL, PHP, Perl, Python (e-commerce, online stores, web presentations and corporate portals). Supervision and maintenance of existing applications.", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Contact: Tomasz Pawlowski, (+48 42) 235 1000, http://www.forweb.pl, Lodz, Poland', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(53, $this->_iY - 0.1); $this->Cell(10, 2.2, 'References', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/references_forweb.zip'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/references_forweb.zip');
		$this->SetXY(40, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Examples', '', 0, 'C', FALSE, 'http://cv.creolink.pl/examples/forweb.zip'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/examples/forweb.zip');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2003, 2005, 2007 - 2009 (commissions)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Portal designer / PHP Web Developer', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'STAWOZ SP Z O.O', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Design and creation of proprietary system of web portals with CMS about enviromental, fire and safety subjects (using PHP, MySQL, XHTML, CSS, JS). Active positioning in Google with very good results, maintenance, development and supervision of portals.", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Contact: Stanisław Woźnica, (+48 42) 673 57 05, (+48 42) 602 290 306, http://www.stawoz.pl, Lodz, Poland', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(31, $this->_iY - 0.1); $this->Cell(10, 2.2, 'References', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/references_stawoz.zip'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/references_stawoz.zip');
		//$this->SetXY(30, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Examples', '', 0, 'C', FALSE, 'http://www.goldenline.pl/jakub-luczynski/'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?download&en');

		
		$this->SetTextColor(90, 90, 90);
		$this->_iY = ($this->GetY() + 5);
		$this->SetXY(8, $this->_iY); $this->SetFont('tahoma', '', 8); $this->Cell(20, 6, '2004 - 2006 (commissions)', 0, 0, 'L', FALSE);
		$this->SetXY(8, ($this->_iY + 5)); $this->SetFont('tahoma', 'B', 11); $this->Cell(100, 6, 'Freelancer / PHP Programmer', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 11)); $this->SetFont('tahoma', '', 9); $this->Cell(100, 6, 'IIZT.COM', 0, 0, 'L', FALSE);
		$this->SetXY(10, ($this->_iY + 17)); $this->SetFont('tahoma', '', 8);
		$this->MultiCell(138, 4, "Creation of websites for Dutch group IIZT - blogs, content management systems - coding with SQL (MySQL), PHP, JavaScript, CSS, HTML/XHTML. Online, remote work.", 0, 'L', FALSE);
		$this->_iY = ($this->GetY() + 1); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, 'Contact: Todd Wilkinson, todd@sacramentomarketinglabs.com, http://iizt.com, Amsterdam, Netherlands', 0, 0, 'R', FALSE);
		$this->SetTextColor(100, 100, 15); 
		$this->SetXY(36, $this->_iY - 0.1); $this->Cell(10, 2.2, 'References', '', 0, 'C', FALSE, 'http://cv.creolink.pl/i/iizt.jpg'); $this->Image('i/save.png', $this->GetX() + 0.5, $this->_iY - 0.2, 2, 2, 'PNG', 'http://cv.creolink.pl/i/iizt.jpg');
		//$this->SetXY(30, $this->_iY - 0.1); $this->Cell(10, 2.2, 'Examples', '', 0, 'C', FALSE, 'http://www.goldenline.pl/jakub-luczynski/'); $this->Image('i/save.png', $this->GetX(), $this->_iY - 0.2, 2, 2, 'PNG', 'http://'.$_SERVER['SERVER_NAME'].'/?download&en');

		
		$this->SetTextColor(90, 90, 90);

		//$this->_iY = ($this->GetY() + 2); $this->SetXY(10, $this->_iY); $this->SetFont('tahoma', '', 6); $this->Cell(138, 2, '...', 0, 0, 'R', FALSE, 'http://pl.linkedin.com/in/jakubluczynski');
	}
	
	private function _agreement()
	{
		$this->SetXY(5, 280);
		$this->SetFont('verdana', '', 6);
		$this->MultiCell(200, 3, "I hereby give consent for my personal data included in my offer to be processed for the purposes of recruitment, in accordance with the\r\nPersonal Data Protection Act dated 29.08.1997 (uniform text: Journal of Laws of the Republic of Poland 2002 No 101, item 926 with further amendments).", 0, 'C', FALSE);
	}
	
	private function _goals()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(245,246,244);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 56, 204, 56);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 50);
		$this->Cell(100, 6, 'Career goals', 0, 0, 'L', FALSE);
		
		$this->SetFont('tahoma', '', 8);
		$this->SetXY(151, 59);
		//$this->MultiCell(54, 4, "In passion, I am a system designer and PHP programmer. From experience, I am project and team manager.\r\n\r\nI feel the best as PHP programmer in big and interesting web projects. I like challenges and new technical solutions. I'm good organized and very thorough. I'm ready to work in team. I am also valued for independent, remote work.", 0, 'L', FALSE);
		$this->MultiCell(54, 4, "My passion is system designing and PHP programming. I have experience in projecting and team management.\r\n\r\nI feel the best as PHP coder in big and interesting web projects. I like challenges and new technical solutions. I'm well organized and very thorough. I'm flexible in team work. My strength is independent and remote work.", 0, 'L', FALSE);
	}
	
	private function _skills()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(90, 90, 90);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 107, 204, 107);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 100);
		$this->Cell(100, 8, 'Technical skills', 0, 0, 'L', FALSE);
		
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
		$this->Cell(100, 6, 'Personality', 0, 0, 'L', FALSE);
		
		$this->SetLineWidth(0.2);
		$this->SetFont('tahoma', '', 8);
		
		$this->_skill(($iY = 181), 'Organization', 6);
		$this->_skill(($iY+=4), 'Reliability', 6);
		$this->_skill(($iY+=4), 'Diligence', 6);
		$this->_skill(($iY+=4), 'Precision', 5);
		$this->_skill(($iY+=4), 'Punctuality', 5);
		//$this->_skill(($iY+=4), 'Communicativity', 4);
		//$this->_skill(($iY+=4), 'Creativity', 4);
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
		$this->Cell(100, 6, 'Languages', 0, 0, 'L', FALSE);
		
		$this->SetLineWidth(0.2);
		$this->SetFont('tahoma', '', 8);
		
		$this->_skill(($iY = 210), 'Polish', 6);
		$this->_skill(($iY+=4), 'English (C1)', 5);
		$this->_skill(($iY+=4), 'German (A2)', 2);
	}
	
	private function _education()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(90, 90, 90);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 231, 204, 231);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 225);
		$this->Cell(100, 6, 'Education & courses', 0, 0, 'L', FALSE);
		
		$this->SetFont('tahoma', '', 8);
		$this->SetXY(151, 233);
		$this->MultiCell(53, 4, "since 2013 intensive English & German course, professional GA training, driving license category B, started studies at the Lodz University of Technology (computer science, 3 years)", 0, 'L', FALSE);
	}
	
	private function _hobby()
	{
		$this->SetDrawColor(150, 150, 150);
		$this->SetFillColor(90, 90, 90);
		$this->SetTextColor(90, 90, 90);
		
		$this->SetLineWidth(0.4); $this->Line(151, 260, 204, 260);
		
		$this->SetFont('dejavu', 'B', 13);
		$this->SetXY(151, 254);
		$this->Cell(100, 6, 'Hobby & Sport', 0, 0, 'L', FALSE);
		
		$this->SetFont('tahoma', '', 8);
		$this->SetXY(151, 262);
		$this->MultiCell(53, 4, "computer games, films and series, computers, programming, stock exchange, football, horse riding, table tennis", 0, 'L', FALSE);
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
		
		///*
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
		//*/
		
		///*
		$this->_references('gandalf_references_en_1'); // references
		$this->_references('gandalf_references_en_2'); // references
		$this->_references('tpnets_en_1'); // references
		$this->_references('tpnets_en_2'); // references
		$this->_references('freepers_en'); // references
		$this->_references('forweb_en_1'); // references
		$this->_references('forweb_en_2'); // references
		$this->_references('forweb_en_3'); // references
		$this->_references('stawoz_en_1'); // references
		$this->_references('stawoz_en_2'); // references
		$this->_references('iizt'); // references
		//*/
		
		//$this->_references('gandalf_references_en_1.small'); // references small monster
		//$this->_references('gandalf_references_en_2.small'); // references small monster
		//$this->_references('tpnets_en_1.small'); // references small monster
		//$this->_references('tpnets_en_2.small'); // references small monster
	}
}
?>
