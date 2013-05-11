import java.sql.SQLException;
import java.sql.Statement;

import org.xml.sax.Attributes;
import org.xml.sax.SAXException;
import org.xml.sax.helpers.DefaultHandler;


public class MyHandler extends DefaultHandler {
		 
	boolean barticle = false;
	boolean bbook = false;
	boolean bthesis = false;
	Publication article;
	Publication book;
	Publication thesis;
	
	String element;
	String authors;
	String schools;
	String editors;
	int i=1;
	
	private Statement stmt;
	
	public MyHandler(Statement stmt) {
		super();
		this.stmt = stmt;
	}
	
	
	public void startElement(String uri, String localName,String qName, 
                Attributes attributes) throws SAXException {
		
		
		//System.out.println("Start Element :" + qName);
		element=qName;
		
		
		if (qName.equalsIgnoreCase("article")) {
			System.out.println("Start Element :" + qName);
			barticle = true;
			article=new Publication();
		}
 
		if (qName.equalsIgnoreCase("book")) {
			System.out.println("Start Element :" + qName);
			bbook = true;
			book=new Publication();
		}
 
		if (qName.equalsIgnoreCase("phdthesis")) {
			System.out.println("Start Element :" + qName);
			bthesis = true;
			thesis=new Publication();
			thesis.phd=true;
		}
		if (qName.equalsIgnoreCase("mastersthesis")) {
			System.out.println("Start Element :" + qName);
			bthesis = true;
			thesis=new Publication();
			thesis.master=true;
		}
		
 
		
 
	}
 
	public void endElement(String uri, String localName,
		String qName) throws SAXException {
 
		//System.out.println("End Element :" + qName);
		
		if (barticle ) {
			if(qName=="editor"&&  Integer.valueOf(article.year)<=2010 &&  Integer.valueOf(article.year)>=2008){
				try {
					stmt.executeUpdate("insert into Editor(Ename) values('"+editors+"')");
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			if (qName == "author" &&  Integer.valueOf(article.year)<=2010 &&  Integer.valueOf(article.year)>=2008){//change this because year comes always after author
				try {
					stmt.executeUpdate("insert into Author(author) values('"+authors+"')");
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			if(qName=="article"){
				//boolean verification=(Integer.valueOf(article.year)<=1995 &&  Integer.valueOf(article.year)>=1990);
				if( Integer.valueOf(article.year)<=2010 &&  Integer.valueOf(article.year)>=2008){
					try {
						System.out.println("insert into Publications(DBLP_KEY,title,url,year,publisher) values("
								+i+",'"+article.title+"','"+article.url+"',"+article.year+",'"+article.publisher+"')");
						stmt.executeUpdate("insert into Publications(DBLP_KEY,title,url,year,publisher) values("
								+i+",'"+article.title+"','"+article.url+"',"+article.year+",'"+article.publisher+"')");//les requettes ne fonctionnent pas 
						
						stmt.executeUpdate("insert into Article(DBLP_KEY,volume,number,pages,journal_name,journal_year) values("
								+i+",'"+article.volume+"','"+article.number+"','"+article.pages+"','"+article.journal_name+"',"+article.journal_year+")");
						i++;
					} catch (SQLException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
					
				}
				barticle = false;
			}
			
			
		}
 
		if (bbook) {
			if(qName=="editor" &&  Integer.valueOf(book.year)<=2010 &&  Integer.valueOf(book.year)>=2008){
				try {
					stmt.executeUpdate("insert into Editor(Ename) values('"+editors+"')");
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			if (qName == "author" &&  Integer.valueOf(book.year)<=2010 &&  Integer.valueOf(book.year)>=2008){
				try {
					stmt.executeUpdate("insert into Author(author) values('"+authors+"')");
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			if(qName=="book"){
				if( Integer.valueOf(book.year)<=2010 &&  Integer.valueOf(book.year)>=2008){
					try {
						stmt.executeUpdate("insert into Publications(DBLP_KEY,title,url,year,publisher) values("
								+i+",'"+book.title+"','"+book.url+"',"+book.year+",'"+book.publisher+"')");
						stmt.executeUpdate("insert into Book(DBLP_KEY,volume,isbn) values("+i+",'"+book.isbn+"')");
						i++;
					} catch (SQLException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
					
				}
				bbook = false;
			}
			
		}
 
		if (bthesis) {
			if(qName=="school" &&  Integer.valueOf(thesis.year)<=2010 && Integer.valueOf(thesis.year)>=2008){
				try {
					stmt.executeUpdate("insert into School(Sname) values('"+schools+"')");
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			if (qName == "author" && Integer.valueOf(thesis.year)<=2010 && Integer.valueOf(thesis.year)>=2008){
				try {
					stmt.executeUpdate("insert into Author(author) values('"+authors+"')");
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			if(qName=="mastersthesis" || qName=="phdthesis"){
				if(Integer.valueOf(thesis.year)<=2010 && Integer.valueOf(thesis.year)>=2008){
					//insert into publications table and thesis table
					try {
						stmt.executeUpdate("insert into Publications(DBLP_KEY,title,url,year,publisher) values("
								+i+",'"+thesis.title+"','"+thesis.url+"',"+thesis.year+",'"+thesis.publisher+"')");
						stmt.executeUpdate("insert into Thesis(DBLP_KEY,masterifTrue,isbnPhd) values("+i+",'"+thesis.master+"','"+thesis.isbn+"')");
						i++;
						
					} catch (SQLException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}

				}
				bthesis = false;
			}
			
			
		}
 
		
	}
 
	public void characters(char ch[], int start, int length) throws SAXException {
 
		//System.out.println("--------------ELEMENT:"+element+"--->"+new String(ch, start, length));
		if(barticle){
			
			verif(article,authors, element,new String(ch, start, length));
			
			if(element=="volume"){
				article.volume= new String(ch, start, length);
			}else if(element=="number"){
				article.number=new String(ch, start, length);
			}else if(element=="pages"){
				article.pages=new String(ch, start, length);
			}else if(element=="journal"){
				//ou est ce qu'on trouve le year du journal???? Plusieurs jounaux pour un meme article?
				article.journal_name=new String(ch, start, length);
			}else if(element=="editor"){
				editors=new String(ch, start, length);
			}
		}
		if (bbook) {
			
			verif(book,authors, element,new String(ch, start, length));
			
			if(element=="isbn"){
				book.isbn=new String(ch, start, length);
			}else if(element=="editor"){
				editors=new String(ch, start, length);
			}
		}
 
		if (bthesis) {
			
			verif(thesis,authors, element,new String(ch, start, length));
			
			if(element=="school"){
				schools=new String(ch, start, length);
			}else if(element=="isbn"){
				thesis.isbn=new String(ch, start, length);
			}
			
		}
 
	}
	
	private void verif(Publication pub,String authors, String element, String finalElement){
		if (element=="author") {
			authors = finalElement;
			
		}else if (element=="title"){//title en plusieurs lignes;
			pub.title=finalElement;
		}else if (element=="year"){
			pub.year=finalElement;
			System.out.println(Integer.valueOf(pub.year));
		}else if (element=="url"){
			pub.url=finalElement;
		}else if (element=="publisher"){
			pub.publisher=finalElement;
		}
	}
	
}
