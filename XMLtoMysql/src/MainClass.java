
import java.net.URL;
import java.sql.Connection;
import java.sql.Statement;
import java.sql.DriverManager;
import java.sql.SQLException;


import javax.xml.parsers.SAXParser;
import javax.xml.parsers.SAXParserFactory;

import org.xml.sax.Attributes;
import org.xml.sax.SAXException;
import org.xml.sax.helpers.DefaultHandler;

public class MainClass {//http://www.java2s.com/Code/Java/Database-SQL-JDBC/Createtableformysqldatabase.htm

  private static final String USER_TABLE = "create table if not exists User (email VARCHAR(20), password VARCHAR(20), administrator BOOLEAN not null default 0)";//0 (meaning false) or 1 (meaning true)
  private static final String PUBLICATION_TABLE = "create table  if not exists Publications (DBLP_KEY INT PRIMARY KEY, title VARCHAR(255), url VARCHAR(255), year YEAR(4),publisher VARCHAR(20))";
  private static final String AUTHOR_TABLE = "create table if not exists Author (DBLP_WWW_KEY INT PRIMARY KEY AUTO_INCREMENT,name VARCHAR(50), number INT)";
  private static final String PUBLICATION_AUTHOR_TABLE = "create table if not exists PublicationsAuthor (DBLP_KEY_PUBL INT PRIMARY KEY, DBLP_KEY_AUTHOR INT)";
  //how to make double primary key??
  private static final String ARTICLE_TABLE = "create table if not exists Article (DBLP_KEY_PUBL INT PRIMARY KEY, volume VARCHAR(10),number VARCHAR(15), pages VARCHAR(15),journal_name VARCHAR(20),journal_year YEAR(4))";
  //que est ce que c'est pages? Est ce qu'on fait une table pour les journaux ou on met ses infos sir la table article vu que il y a un seul par article.
  private static final String BOOK_TABLE = "create table if not exists Book (DBLP_KEY INT PRIMARY KEY, isbn VARCHAR(20))";
  private static final String THESIS_TABLE = "create table if not exists Thesis (DBLP_KEY INT PRIMARY KEY,masterifTrue BOOLEAN not null,isbnPhd VARCHAR(20))";
  private static final String EDITOR_TABLE = "create table if not exists Editor (DBLP_KEY INT PRIMARY KEY AUTO_INCREMENT, Ename VARCHAR(20) )";
  //Une ou deux tables pour editor?
  private static final String SCHOOL_TABLE = "create table if not exists School (DBLP_KEY INT PRIMARY KEY AUTO_INCREMENT, Sname VARCHAR(30) )";
  //Une ou deux tables pour school?
  
  
  public static Connection getConnection() throws Exception {
    String driver = "com.mysql.jdbc.Driver";
    String url = "jdbc:mysql://localhost/test";
    String username = "root";
    String password = "";
    Class.forName(driver);
    Connection conn = DriverManager.getConnection(url, username, password);
    return conn;
  }

  public static void main(String args[]) {
    Connection conn = null;
    Statement stmt = null;
    try {
      conn = getConnection();
      stmt = conn.createStatement();
      stmt.executeUpdate(USER_TABLE);
      stmt.executeUpdate(PUBLICATION_TABLE);
      stmt.executeUpdate(AUTHOR_TABLE);
      stmt.executeUpdate(PUBLICATION_AUTHOR_TABLE);
      stmt.executeUpdate(ARTICLE_TABLE);
      stmt.executeUpdate(BOOK_TABLE);
      stmt.executeUpdate(THESIS_TABLE);
      stmt.executeUpdate(EDITOR_TABLE);
      stmt.executeUpdate(SCHOOL_TABLE);
     
      stmt.executeUpdate("insert into User(email, password) values('rootemail', 'rootpassword')");
      //stmt.executeUpdate("insert into MyEmployees3(id, firstName) values(200, 'B')");
      ReadXMLFile(stmt);
      System.out.println("CreateEmployeeTableMySQL: main(): table created.");
    } catch (ClassNotFoundException e) {
      System.out.println("error: failed to load MySQL driver.");
      e.printStackTrace();
    } catch (SQLException e) {
      System.out.println("error: failed to create a connection object.");
      e.printStackTrace();
    } catch (Exception e) {
      System.out.println("other error:");
      e.printStackTrace();
    } finally {
      try {
        stmt.close();
        conn.close();        
      } catch (SQLException e) {
        e.printStackTrace();
      }
    }
  }
  public static void  ReadXMLFile(final Statement stmt) {
	  	
	  	DefaultHandler handler;
	  	
		    try {
		 
		    	SAXParserFactory spf = SAXParserFactory.newInstance();
		    	SAXParser sp = spf.newSAXParser();
		    	String link ="http://dblp.uni-trier.de/xml/dblp.xml";
		    	URL linkURL = new URL(link);
		 
				handler = new DefaultHandler() {
			 
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
		 
				};
				
				sp.parse(link, handler);//-------------------------http://stackoverflow.com/questions/8722122/passing-a-http-url-into-saxparser-xml
		    
		 
			 } catch (Exception e) {
			   e.printStackTrace();
			 }
		    
  	}
  
	private static void verif(Publication pub,String authors, String element, String finalElement){
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
  
  //Il reste a reviser les requettes sql et les types qui sont fournis au differents tables.
	
}