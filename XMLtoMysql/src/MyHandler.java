import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.Queue;

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
	Queue<String> authors;
	Queue<String> schools;
	Queue<String> editors;
	int i=1;
	int authorNumber=1;
	int editorNumber=1;
	int schoolNumber=1;
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
			authors=new LinkedList<String>();
			editors=new LinkedList<String>();
		}
 
		if (qName.equalsIgnoreCase("book")) {
			System.out.println("Start Element :" + qName);
			bbook = true;
			book=new Publication();
			authors=new LinkedList<String>();
			editors=new LinkedList<String>();
		}
 
		if (qName.equalsIgnoreCase("phdthesis")) {
			System.out.println("Start Element :" + qName);
			bthesis = true;
			thesis=new Publication();
			thesis.phd=true;
			authors=new LinkedList<String>();
			schools=new LinkedList<String>();
		}
		if (qName.equalsIgnoreCase("mastersthesis")) {
			System.out.println("Start Element :" + qName);
			bthesis = true;
			thesis=new Publication();
			thesis.master=true;
			authors=new LinkedList<String>();
			schools=new LinkedList<String>();
		}
		
 
		
 
	}
 
	public void endElement(String uri, String localName,
		String qName) throws SAXException {
 
		//System.out.println("End Element :" + qName);
		
		if (barticle ) {
			
			if(qName=="article"){
				//boolean verification=(Integer.valueOf(article.year)<=1995 &&  Integer.valueOf(article.year)>=1990);
				if( Integer.valueOf(article.year)<=2010 &&  Integer.valueOf(article.year)>=2008){
					try {
						System.out.println("insert ignore into Publications(DBLP_KEY,title,url,year,publisher) values("
								+"'"+article.title+"','"+article.url+"',"+article.year+",'"+article.publisher+"')");
						stmt.executeUpdate("insert ignore into Publications(title,url,year,publisher) values("
								+"'"+article.title+"','"+article.url+"',"+article.year+",'"+article.publisher+"')");
						i=getLastRowNumber(i);
						System.out.println("insert ignore into Article(DBLP_KEY_PUBL,volume,number,pages,journal_name,journal_year) values("
								+i+",'"+article.volume+"','"+article.number+"','"+article.pages+"','"+article.journal_name+"',"+article.journal_year+")");
						stmt.executeUpdate("insert ignore into Article(DBLP_KEY_PUBL,volume,number,pages,journal_name,journal_year) values("
								+i+",'"+article.volume+"','"+article.number+"','"+article.pages+"','"+article.journal_name+"',"+article.journal_year+")");
						
						while(! authors.isEmpty()){
							String authorCur=authors.remove();
							String authorCurNumber="1";
							ResultSet rs;
							rs=stmt.executeQuery("select DBLP_KEY_AUTHOR from Author where Aname='"+authorCur+"'");
							if (rs.next()){//si l'autheur existe deja
								authorCurNumber=rs.getString(1);
								//stmt.executeUpdate("insert ignore into Author(DBLP_KEY_AUTHOR,Aname) values("+rs.getString(1)+",'"+authorCur+"')");
								stmt.executeUpdate("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorCurNumber+")");
							}else {
								//System.out.println("insert ignore into Author(DBLP_KEY_AUTHOR,Aname) values("+authorNumber+",'"+authors.remove()+"')");
								stmt.executeUpdate("insert ignore into Author(Aname) values('"+authorCur+"')");
								authorNumber=getLastRowNumber(authorNumber);
								System.out.println("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorNumber+")");
								stmt.executeUpdate("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorNumber+")");
								authorNumber++;
							}
						}
						while(! editors.isEmpty()){
							String editorCur=editors.remove();
							String editorCurNumber="1";
							ResultSet rs;
							rs=stmt.executeQuery("select DBLP_KEY_EDITOR from Editor where Ename='"+editorCur+"'");
							if (rs.next()){
								editorCurNumber=rs.getString(1);
								stmt.executeUpdate("insert ignore into EditorPublication(DBLP_KEY_EDITOR INT PRIMARY KEY, DBLP_KEY) values("+editorCurNumber+", "+i+")");
							}else{
								//System.out.println("insert ignore into Editor(DBLP_KEY_EDITOR, Ename) values("+editorNumber+", '"+editors.remove()+"')");
								stmt.executeUpdate("insert ignore into Editor(Ename) values('"+editorCur+"')");
								editorNumber=getLastRowNumber(editorNumber);
								System.out.println("insert ignore into EditorPublication(DBLP_KEY_EDITOR INT PRIMARY KEY, DBLP_KEY) values("+editorNumber+", "+i+")");
								stmt.executeUpdate("insert ignore into EditorPublication(DBLP_KEY_EDITOR INT PRIMARY KEY, DBLP_KEY) values("+editorNumber+", "+i+")");
								editorNumber++;
							}
						}
						i++;
					} catch (SQLException e) {
						System.out.println("ERROR");
						e.printStackTrace();
					}
					
				}
				barticle = false;
			}
			
			
		}
 
		if (bbook) {
			
			if(qName=="book"){
				if( Integer.valueOf(book.year)<=2010 &&  Integer.valueOf(book.year)>=2008){
					try {
						System.out.println("insert ignore into Publications(title,url,year,publisher) values("
								+"'"+book.title+"','"+book.url+"',"+book.year+",'"+book.publisher+"')");
						stmt.executeUpdate("insert ignore into Publications(title,url,year,publisher) values("
								+"'"+book.title+"','"+book.url+"',"+book.year+",'"+book.publisher+"')");
						i=getLastRowNumber(i);
						System.out.println("insert ignore into Book(DBLP_KEY,volume,isbn) values("+i+",'"+book.isbn+"')");
						stmt.executeUpdate("insert ignore into Book(DBLP_KEY,volume,isbn) values("+i+",'"+book.isbn+"')");
						while(! authors.isEmpty()){
							String authorCur=authors.remove();
							String authorCurNumber="1";
							ResultSet rs;
							rs=stmt.executeQuery("select DBLP_KEY_AUTHOR from Author where Aname='"+authorCur+"'");
							if (rs.next()){//si l'autheur existe deja
								authorCurNumber=rs.getString(1);
								//stmt.executeUpdate("insert ignore into Author(DBLP_KEY_AUTHOR,Aname) values("+rs.getString(1)+",'"+authorCur+"')");
								stmt.executeUpdate("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorCurNumber+")");
							}else {
								//System.out.println("insert ignore into Author(DBLP_KEY_AUTHOR,Aname) values("+authorNumber+",'"+authors.remove()+"')");
								stmt.executeUpdate("insert ignore into Author(Aname) values('"+authorCur+"')");
								authorNumber=getLastRowNumber(authorNumber);
								System.out.println("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorNumber+")");
								stmt.executeUpdate("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorNumber+")");
								authorNumber++;
							}
						}
						while(! editors.isEmpty()){
							String editorCur=editors.remove();
							String editorCurNumber="1";
							ResultSet rs;
							rs=stmt.executeQuery("select DBLP_KEY_EDITOR from Editor where Ename='"+editorCur+"'");
							if (rs.next()){
								editorCurNumber=rs.getString(1);
								stmt.executeUpdate("insert ignore into EditorPublication(DBLP_KEY_EDITOR INT PRIMARY KEY, DBLP_KEY) values("+editorCurNumber+", "+i+")");
							}else{
								//System.out.println("insert ignore into Editor(DBLP_KEY_EDITOR, Ename) values("+editorNumber+", '"+editors.remove()+"')");
								stmt.executeUpdate("insert ignore into Editor(Ename) values('"+editorCur+"')");
								editorNumber=getLastRowNumber(editorNumber);
								System.out.println("insert ignore into EditorPublication(DBLP_KEY_EDITOR INT PRIMARY KEY, DBLP_KEY) values("+editorNumber+", "+i+")");
								stmt.executeUpdate("insert ignore into EditorPublication(DBLP_KEY_EDITOR INT PRIMARY KEY, DBLP_KEY) values("+editorNumber+", "+i+")");
								editorNumber++;
							}
						}
						i++;
					} catch (SQLException e) {
						System.out.println("ERROR");
						e.printStackTrace();
					}
					
				}
				bbook = false;
			}
			
		}
 
		if (bthesis) {

			if(qName=="mastersthesis" || qName=="phdthesis"){
				if(Integer.valueOf(thesis.year)<=2010 && Integer.valueOf(thesis.year)>=2008){
					//insert into publications table and thesis table
					try {
						System.out.println("insert ignore into Publications(title,url,year,publisher) values("
								+"'"+thesis.title+"', '"+thesis.url+"', "+thesis.year+",'"+thesis.publisher+"')");
						stmt.executeUpdate("insert ignore into Publications(title,url,year,publisher) values("
								+"'"+thesis.title+"', '"+thesis.url+"', "+thesis.year+",'"+thesis.publisher+"')");
						i=getLastRowNumber(i);
						System.out.println("insert ignore into Thesis(DBLP_KEY,masterifTrue,isbnPhd) values("+i+", "+thesis.master+", '"+thesis.isbn+"')");
						stmt.executeUpdate("insert ignore into Thesis(DBLP_KEY,masterifTrue,isbnPhd) values("+i+", "+thesis.master+", '"+thesis.isbn+"')");
						while(! authors.isEmpty()){
							String authorCur=authors.remove();
							String authorCurNumber="1";
							ResultSet rs;
							rs=stmt.executeQuery("select DBLP_KEY_AUTHOR from Author where Aname='"+authorCur+"'");
							if (rs.next()){//si l'autheur existe deja
								authorCurNumber=rs.getString(1);
								//stmt.executeUpdate("insert ignore into Author(DBLP_KEY_AUTHOR,Aname) values("+rs.getString(1)+",'"+authorCur+"')");
								stmt.executeUpdate("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorCurNumber+")");
							}else {
								//System.out.println("insert ignore into Author(DBLP_KEY_AUTHOR,Aname) values("+authorNumber+",'"+authors.remove()+"')");
								stmt.executeUpdate("insert ignore into Author(Aname) values('"+authorCur+"')");
								authorNumber=getLastRowNumber(authorNumber);
								System.out.println("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorNumber+")");
								stmt.executeUpdate("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+authorNumber+")");
								authorNumber++;
							}
						}
						while(! schools.isEmpty()){
							String schoolCur=schools.remove();
							String schoolCurNumber="1";
							ResultSet rs;
							rs=stmt.executeQuery("select DBLP_KEY_AUTHOR from Author where Aname='"+schoolCur+"'");
							if (rs.next()){
								schoolCurNumber=rs.getString(1);
								//stmt.executeUpdate("insert ignore into Author(DBLP_KEY_AUTHOR,Aname) values("+rs.getString(1)+",'"+authorCur+"')");
								stmt.executeUpdate("insert ignore into PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) values("+i+", "+schoolCurNumber+")");
							}else{
								//System.out.println("insert ignore into School(DBLP_KEY, Sname) values("+schoolNumber+", '"+schools.remove()+"')");
								stmt.executeUpdate("insert ignore into School(Sname) values('"+schoolCur+"')");
								schoolNumber=getLastRowNumber(schoolNumber);
								System.out.println("insert ignore into SchoolThesis(DBLP_KEY, DBLP_KEY_SCH) values("+i+", "+schoolNumber+")");
								stmt.executeUpdate("insert ignore into SchoolThesis(DBLP_KEY, DBLP_KEY_SCH) values("+i+", "+schoolNumber+")");
								schoolNumber++;
							}
							
						}
						i++;
						
					} catch (SQLException e) {
						System.out.println("ERROR");
						e.printStackTrace();
					}

				}
				bthesis = false;
			}
			
			
		}
 
		
	}
 
	public void characters(char ch[], int start, int length) throws SAXException {
 
		String output; 
		//System.out.println("--------------ELEMENT:"+element+"--->"+new String(ch, start, length));
		if(barticle){
			
			output= verifString(new String(ch, start, length));
			verif(article,authors, element,output);
			
			if(element=="volume"){
				article.volume= output;
			}else if(element=="number"){
				article.number=output;
			}else if(element=="pages"){
				article.pages=output;
			}else if(element=="journal"){
				//ou est ce qu'on trouve le year du journal???? Plusieurs jounaux pour un meme article?
				article.journal_name=output;
			}else if(element=="editor"){
				editors.add(output);
			}
		}
		if (bbook) {
			output= verifString(new String(ch, start, length));
			verif(book,authors, element,output);
			
			if(element=="isbn"){
				book.isbn=output;
			}else if(element=="editor"){
				editors.add(output);
			}
		}
 
		if (bthesis) {
			output= verifString(new String(ch, start, length));
			verif(thesis,authors, element,output);
			
			if(element=="school"){
				schools.add(output);
			}else if(element=="isbn"){
				thesis.isbn=output;
			}
			
		}
 
	}
	
	private void verif(Publication pub,Queue<String> authors, String element, String finalElement){
		if (element=="author") {
			authors.add(finalElement);
			
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
	private String verifString(String a){
		if(a.contains("'")){
			a=a.replace("'", "\\'");
		}
		return a;
	}
	
	private int getLastRowNumber(int n){
		ResultSet rs;
		try {
			rs=stmt.executeQuery("select LAST_INSERT_ID()");
			rs.next();
			n=Integer.valueOf(rs.getString(1));
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return n;
	}
}
