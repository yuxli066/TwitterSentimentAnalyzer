#!/usr/bin/perl
my $start = time;
exit if $#ARGV == 1;
$hashtag = $ARGV[0];
open(my $csv, "<", "./total_pol.csv") || die "Can't open total_pol.csv: $!";
while (my $line = <$csv>) {
	chomp $line;

	my @fields = split "," , $line;
	if ($ARGV[0] eq $fields[0]) {
		print "$fields[0]"
	}
}
close $csv;
my $duration = time - $start;
print "\nExecution time: $duration s\n";
