
import java.sql.Connection;
import java.sql.Statement;
import java.sql.DriverManager;
import java.sql.SQLException;


import javax.xml.parsers.SAXParser;
import javax.xml.parsers.SAXParserFactory;

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
		    	handler = new MyHandler(stmt);
				
				sp.parse(link, handler);//-------------------------http://stackoverflow.com/questions/8722122/passing-a-http-url-into-saxparser-xml
		    
		 
			 } catch (Exception e) {
			   e.printStackTrace();
			 }
		    
  	}
 
  
  //Il reste a reviser les requettes sql et les types qui sont fournis au differents tables.
	
}