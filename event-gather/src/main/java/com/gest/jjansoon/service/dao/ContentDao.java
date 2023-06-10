package com.gest.jjansoon.service.dao;

import java.util.List;
import java.util.Map;

import javax.annotation.Resource;

import org.mybatis.spring.SqlSessionTemplate;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Repository;
import org.springframework.transaction.support.TransactionTemplate;

@Repository
public class ContentDao {

	private static final Logger logger = LoggerFactory.getLogger(ContentDao.class);

	public static final String NAMESPACE = "contentMapper";

	public static double fixCount = 100000;

	@Resource(name = "sqlSessionTemplategestdb")
	private SqlSessionTemplate db;
	@Autowired
	private TransactionTemplate transactionTemplate;

	
	public Map<String, String> selectNow(){
		return db.selectOne(String.format("%s.%s", NAMESPACE, "selectNow"));
	}
	
	public Map<String, String> selectBoardReview(){
		return db.selectOne(String.format("%s.%s", NAMESPACE, "selectBoardReview"));
	}
	public void insertBoardReview(Map<String, String> param) {
		this.db.update(String.format("%s.%s", NAMESPACE, "insertBoardReview"), param);
	}
	public void updateBoardReviewSucc(Map<String, String> param) {
		this.db.update(String.format("%s.%s", NAMESPACE, "updateBoardReviewSucc"), param);
	}
	
	public List<Map> selectContentDom() {
		List<Map> list = this.db.selectList(String.format("%s.%s", NAMESPACE, "selectContentDom"));
		return list;
	}
	public Map<String, String> selectContentDomDetail(Map<String, String> idx) {
		Map<String, String> result = this.db.selectOne(String.format("%s.%s", NAMESPACE, "selectContentDomDetail"), idx);
		return result;
	}
	
	public void updateContentUpdateDates(Map<String, String> param) {
		this.db.update(String.format("%s.%s", NAMESPACE, "updateContentUpdateDates"), param);
	}
	public List<Map> selectContent() {
		List<Map> list = this.db.selectList(String.format("%s.%s", NAMESPACE, "selectContent"));
		return list;
	}

	public Map selectContentfromKey(Map param) {
		Map<String, String> result = this.db.selectOne(String.format("%s.%s", NAMESPACE, "selectContentfromKey"), param);
		return result;
	}
	public void insertContent(Map param) {
		try {
			db.update(String.format("%s.%s", NAMESPACE, "insertContent"), param);
		}catch(Exception e) {
			logger.error("err ", e);
		}
	}
}