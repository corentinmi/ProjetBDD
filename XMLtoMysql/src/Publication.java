
public class Publication {

	
	public String title;
	public String url;
	public String year;	
	public String publisher;	
	
	public String isbn;
	
	public String volume;
	public String number;
	public String pages;
	public String journal_name;
	public String journal_year;
	
	public boolean master;
	public boolean phd;
	
	public Publication(){
		
		title="no title";
		url="no website";
		publisher="unknown";
		
		journal_name="no journal";	
		master=false;
		phd=false;
		isbn="";
		year="2000";
		volume="no volume";
		number="";
		pages="";
		journal_year="2000";
		
		
				
	}
}
