#!/usr/bin/perl
#
# This file uses ETP which can be found at
# http://perl.eae.net/etp/
#

use lib "../../..";
use Etp;

PrintOutput();

sub PrintOutput() {
	my $etp = new Etp();
	$etp->parseInput();
	my $counter = $etp->getInput('counter') || 0;
	print("Content-Type: text/xml; charset=UTF-8\n");
	print("Cache-Control: no-cache\n");
	print("Last-Modified: " . $etp->getDate(0, 3) . "\n");
	print("\n");
	print("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
	print("<tree><tree text=\"" . $counter . "\" src=\"nest.xml.pl?counter=" .
		  ($counter + 1) . "\"/></tree>\n");
}
